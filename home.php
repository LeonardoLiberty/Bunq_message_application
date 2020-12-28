<?php

include 'SQLite_connection.php';

session_start();
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
        } else {
           # echo "user added successfully\n";
        }
        # $db->close();

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

		$sql = 'UPDATE user  SET online_status = 2, timestamps = "'.strval(time()).'" WHERE user_id="'.$_SESSION["name"].'"';
	}
     $ret = $db->exec($sql);
        if(!$ret) {
            echo $db->lastErrorMsg();
        } else {
            # echo "user added successfully\n";
        }

        setcookie("name", $_SESSION["name"]);

}


?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bunq Home assignment</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
<form>
    <label for="recipient">Send to:</label><br>
    <input type="text" id="recipient" name="recipient"><br>
    <label for="msg">Massage:</label><br>
    <input type="text" id="msg" name="msg">
</form>
<button id='submit'>submit</button> 

</body>

<script type="text/javascript">
    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    //console.log('cookie: '+ getCookie('name'));

	$('#submit').on( "click", function() {
        let msg = document.getElementById("msg").value;
        let recipient = document.getElementById("recipient").value;
        if(!msg && !recipient){
            alert("please input some massages");
            return;
        }
        console.log( msg );
  
      $.ajax({
          url: "/api",
          type: 'POST',
          data: {recipient: recipient, msg: msg, status:1, sender: getCookie('name')},
          dataType: 'json',

          success: function(res) {
                console.log(res);
          },

          error: function(err) {
              console.log(err.responseText)
          }

      });
    });
</script>

</html>