<?

 include('../config.php');
 include('functions/functions.php');
 
 $db = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
 
 if(!$db):   // Если соединиться не удалось:
   print ("Datebase connection failed.");
   exit();
 endif;

 if(!mysql_select_db(DB_BASE_NAME)):
   print ("Datebase select failed.");
   exit();
 endif;

 // Если нажата кнопка с именем blogin (Авторизация)
 if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['blogin'])):
   // Если поле логина или пароля пустое - делаем редирект
   if(empty($_POST['name']) || empty($_POST['pass'])):
      redirect("fields");  
   endif; 
   // В протовном случае переходим к авторизации
   auth();
 endif;

 // Если у поьзователя стоят куки и функция check() скажет что пользователь залогинен
 if(isset($_COOKIE['time']) && isset($_COOKIE['name']) && check() == TRUE):
    //include('functions/old_index.php');
    $template = '__home.php';
    if(isset($_GET['page'])):
       require_once '../classes/MySql.php';
       $mysql = new Mysql($host,$user,$pass,$dbnm);
       $page = $_GET['page']; 
       $className = ucfirst($page);
       require_once 'class/'.$className.'.php';
       $obj = new $className();
       $mod = $_GET['mod']; 
       $template = '__'.$mod.'.php';
    endif;
    
  else:
  
    if(!empty($_GET['task'])):
      switch($_GET['task']):
        case "nepass":   
              $message = "Passwords do not match";
              break;
        case "not_auth": 
              $message = "Username or password is incorrect";
              break;
        case "fields":   
              $message = "Not all fields";
              break;
        case "exists":   
              $message = "A user with the same name already exists";
              break;
       endswitch;
     endif;
     $template = '__login.php';
     
  endif;
  
  include('views/__index.php');
?>