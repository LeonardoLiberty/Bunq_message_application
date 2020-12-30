<?php
/*
 * Without login, it will use timestamps to create a new token automatically.
 */
session_start();
include "src/session.php";


?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bunq Home assignment</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="src/action.js"></script>
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
    /*
    Once click the submit button,
    it will post a message to the recipient
    NaN are not allowed.
     */
	$('#submit').on( "click", function() {
        let msg = document.getElementById("msg").value;
        let recipient = document.getElementById("recipient").value;
        if(!msg || !recipient){
            alert("please input recipient and massages");
            return;
        }

      $.ajax({
          url: "/api/pop",
          type: 'POST',
          data: {recipient: recipient, msg: msg, status:0, sender: getCookie('name')},
          dataType: 'json',

      });
    });
</script>

</html>