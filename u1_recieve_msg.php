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

<div id="container">
    <h1 id="um">unread messages</h1>
    <div class="unread">

    </div>
    <h1 id="hm">historical messages</h1>
    <div class="history">

    </div>
</div>
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
                let data = JSON.parse(res);
                let chat_ids = $("#container").children().children().map((i, div) => div.id).get();
                chat_ids = chat_ids.map(x => parseInt(x));
                let read_chat = [];
                for (let i=0; i<data.length; i++){
                    if(!chat_ids.includes(data[i]['chat_id'])){
                        if(data[i]['status'] === 1){
                            $(".unread").append("<div id="+ data[i]['chat_id']+">"+
                                "<p>"+ "sender id: " + data[i]['sender_id'] +"</p>"+
                                "<p>"+ "content: "+ data[i]['chat'] +"</p>"+
                                "<p>"+ "time: " + unix_timestamps_js(data[i]['timestamps']) +"</p>"+
                                "<p>----------------------------------------------------</p>"+
                                "</div>");

                            read_chat.push(data[i]['chat_id']);
                        }else{
                            $(".history").append("<div id="+ data[i]['chat_id']+">"+
                                "<p>"+ "sender id: " + data[i]['sender_id'] +"</p>"+
                                "<p>"+ "content: "+ data[i]['chat'] +"</p>"+
                                "<p>"+ "time: " + unix_timestamps_js(data[i]['timestamps']) +"</p>"+
                                "<p>----------------------------------------------------</p>"+
                                "</div>");
                        }
                    }
                }

                $.ajax({
                    url: "/api/read_msg",
                    type: 'POST',
                    data: {chat_id: read_chat},
                    dataType: 'json',

                    success: function(res) {
                        console.log(res);
                    }
                });

            },
            error: function(err) {
                console.log(err.responseText);
                console.log('err');
            }

        });

    }
</script>
</html>
