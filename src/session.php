<?php
include 'src/SQLite_connection.php';


/*session is started if you don't write this line can't use $_Session  global variable*/
if(!$_SESSION["name"]){

    $_SESSION["name"]='New_user_'.strval(time());
    print($_SESSION["name"]);

    $db = new MyDB();
    $sql = 'INSERT OR IGNORE INTO user (user_id, user_name, online_status) VALUES ("'.$_SESSION["name"].'", "'.$_SESSION["name"].'", 1)';
    # echo $sql;

    if($db){
        $ret = $db->exec($sql);
        if(!$ret) {
            echo $db->lastErrorMsg();
        }

    }

}
else{
    $db = new MyDB();
    $check_log = 'SELECT timestamps from user where user_id="'.$_SESSION["name"].'"';

    $check_log = $db->query($check_log);
    $check_log = $check_log->fetchArray();
    $sql = null;
    if(!$check_log){
        $sql = 'INSERT OR IGNORE INTO user (user_id, user_name, online_status, timestamps) VALUES ("'.$_SESSION["name"].'", "'.$_SESSION["name"].'", 1, '.strval(time()).')';

    }else{

        $sql = 'UPDATE user  SET online_status = 1, timestamps = "'.strval(time()).'" WHERE user_id="'.$_SESSION["name"].'"';
    }

    $ret = $db->exec($sql);
    if(!$ret) {
        echo $db->lastErrorMsg();
    }
}

setcookie("name", $_SESSION["name"]);