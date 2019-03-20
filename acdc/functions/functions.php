<?

// Функция получения IP пользователя
function get_ip(){
   if($ip = getenv("HTTP_CLIENT_IP")) return $ip;
   if($ip = getenv("HTTP_X_FORWARDED_FOR")):
      if($ip == '' || $ip == "unknown") return getenv("REMOTE_ADDR");
   endif;
   if($ip = getenv("REMOTE_ADDR"))return $ip;
}

// Вычисляе хэш безопасности
function hashs($user,$user_agent,$user_ip,$tm){ 
   return md5($user).md5($user_agent).md5($user_ip).md5($tm);
}

// Получаем текущее время с учетом дробных частей секунды
function getmicrotime(){
   $mt = explode(" ",microtime());
   return ((float)$mt[0] + (float)$mt[1]);
}

// Редирект с параметром
function redirect($par){
   if($par == "exit"):
      header("Location: ".ADMIN_NAME);
   else:
      header("Location: ".ADMIN_NAME."?task=".$par);
   endif; 
   exit;
}

// Продление сессии (проверка, что пользователь - авторизован)
function check(){
   // Ищем пользователя с именем, указанным в куках
   $result = mysql_query("SELECT * FROM __auth_members WHERE name = '".mysql_real_escape_string($_COOKIE['name'])."' LIMIT 1");
   // Еслии пользователя с таким именем нет, делаем редирект
   if(mysql_num_rows($result)==0):
      setcookie("time", "", time());
      setcookie("name", "", time());
      redirect("not_auth");
   endif;
   // Парсим полученную запись.
   global $user;
   $user = mysql_fetch_object($result);
   // Количсетво минут для жизни сессии
   $minutes = 60*12;
   $ss = "SELECT * FROM __auth_sessions WHERE member = ".$user->id." AND hash = '".hashs($user->name,$_SERVER['HTTP_USER_AGENT'],get_ip(),$_COOKIE['time'])."'AND time > ".(time()-($minutes * 60))." LIMIT 1";
   // Ищем, есть ли активная сессия
   $result = mysql_query($ss);
   // Если нету активной сессии, перекидываем на фору авторизации
   if(mysql_num_rows($result)==0):
      setcookie("time", "", time());
      setcookie("name", "", time());
      redirect("not_auth");
   endif;
   // Если авторизированный пользователь хочет выйти
   if(isset($_GET['quit'])):
     // Удаляем запись о сессии пользователя
     $ds = "DELETE FROM __auth_sessions WHERE member = ".$user->id." AND hash = '".hashs($user->name,$_SERVER['HTTP_USER_AGENT'],get_ip(),$_COOKIE['time'])."'";
     mysql_query($ds);
     // чищаем куки и усианавливаем время жизни в прошлом
     setcookie("time", "", time());
     setcookie("name", "", time());
     // Возвращаем значение "Ложь"
     redirect("exit");
   else:
     // Иначе, т.е. если пользователь не хочет выходить
     // Обновляем время последней активности пользователя на текущее.
     mysql_query( "UPDATE __auth_sessions SET time = ".time()." WHERE member = ".$user->id." AND hash = '".hashs($user->name,$_SERVER['HTTP_USER_AGENT'],get_ip(),$_COOKIE['time'])."'");
     // Продлеваем время жизни кук
     @setcookie("time", $_COOKIE['time'], time() + 3600 * 12);
     @setcookie("name", $_COOKIE['name'], time() + 3600 * 12);
     // Возвращаем значение "Истина"
     return TRUE;
   endif;
}

// Функция авторизации
function auth(){
   // Ищем пользователя с именем, указанным в куках
   $sql =  "SELECT * FROM __auth_members WHERE name = '" . mysql_escape_string( $_POST['name'] ) . "' AND password = '" . md5( $_POST['pass'] ) . "' LIMIT 1";
   $result = mysql_query($sql);
   // Еслии пользователя с таким именем нет, делаем редирект
   if(mysql_num_rows($result)==0):
      redirect("not_auth"); 
   endif;
   // Парсим полученную запись.
   $user = mysql_fetch_object($result);
   // Получаем текущее время с учетом дробных частей секунды
   $tm = getmicrotime();
   // Вставляем запись в таблицу с сессиями.
   mysql_query("INSERT INTO __auth_sessions (`member`,`time`,`hash`) VALUES (".$user->id.",".time().",'".hashs($user->name,$_SERVER['HTTP_USER_AGENT'],get_ip(),$tm)."')");
   // Ставим пользователю куки с его логинов и уникальным временем авторизации. Время жизни кук - 15 минут
   setcookie("time", $tm, time() + 3600 * 12);
   setcookie("name", $user->name, time() + 3600 * 12);
   // делам редирект без параметра, т.к. никаких ошибок не было
   redirect("do");
}