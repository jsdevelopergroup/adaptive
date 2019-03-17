<?

class Mysql {
    
    var $conn;
     
    Function __construct($host, $user, $password, $database){
        $this->conn = @mysql_pconnect($host, $user, $password);
        if(false === $this->conn) {

        }
        if(false === @mysql_select_db($database)) {
           return false;
        }
        return true;
    }    
    
    
    Function getQuery($sql){
        $result = mysql_query($sql);
        return $result;
    }
    
    Function resultToArray($result){
        $res_array = array();
        $count = 0;
        while($row = mysql_fetch_array($result)){
              $res_array[$count]=$row;
              $count++; 
        }
        return $res_array;
    }
        
    Function getArray($sql){
        $result = mysql_query($sql);
        $result = $this->resultToArray($result);
        return $result;        
    }
       
    Function getCount($sql){
        $result = mysql_query($sql);
        return @mysql_num_rows($result);        
    }
    
    Function getOneRow($sql){
        $result = mysql_query($sql);
        return mysql_fetch_array($result);      
    }
    
    Function getRowValue($sql,$val){
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        return $row[$val];      
    }    
}
?>