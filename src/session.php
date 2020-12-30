<?php
include 'src/SQLite_connection.php';

/*
 * require session.php after call the session_start()
 */

// If no session name exist, insert new user with "New_user"+timestamps
if(!$_SESSION["name"]){

    $_SESSION["name"]='New_user_'.strval(time());
    $db = new MyDB();
    $sql = 'INSERT OR IGNORE INTO user (user_id, user_name, online_status) VALUES ("'.$_SESSION["name"].'", "'.$_SESSION["name"].'", 1)';
    if($db){
        $ret = $db->exec($sql);
        if(!$ret) {
            echo $db->lastErrorMsg();
        }

    }

}

//If session name exist, check the last log records and update the log timestamps.
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

//pass the session name to cookie name
setcookie("name", $_SESSION["name"]);