<?php

session_start();
$_SESSION["name"] = 'u1';
include "src/session.php";

?>


<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bunq Messages</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="src/action.js"></script>

</head>

<body>

<p>updated</p>
</body>

<script type="text/javascript">

    $( document ).ready(function() {
        if(getCookie('name') === 'u1'){
            console.log('Right');

        }else{
            console.log('not u1');
        }

        fetch();
        setInterval(function () {
            //console.log('it works' + new Date());
           fetch();

        },5000);


    });

    function fetch(){

        $.ajax({
            url: "/api/fetch",
            type: 'POST',
            data: {recipient: getCookie('name')},
            dataType: 'json',

            success: function(res) {
                console.log(res);
                

            },

            error: function(err) {
                console.log(err.responseText);
                console.log('err');
            }

        });

    }
</script>
</html>
