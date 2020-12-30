<?php
/*
 * One user receives message from the other users
 */
session_start();

//set the recipient as u1 which can be modified once add login function
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

    /*
    Once the document loaded ready,
    it will periodically call the fetch function to update the new messages.
    Implemented by JS setInterval function
     */
    $( document ).ready(function() {
        fetch();
        setInterval(function () {
            //console.log('it works' + new Date());
           fetch();

        },5000);

    });

    /*
    ajax api to be called to send requests with urls
    response are in the parameters of success function.
     */
    function fetch(){
        $.ajax({
            url: "/api/fetch",
            type: 'POST',
            data: {recipient: getCookie('name')},
            dataType: 'json',
            success: function(res) {
                let data = JSON.parse(res); //json type received information
                let chat_ids = $("#container").children().children().map((i, div) => div.id).get();
                chat_ids = chat_ids.map(x => parseInt(x));
                let read_chat = [];
                for (let i=0; i<data.length; i++){
                    //if the chats already added in the following section, they will not be added again.
                    if(!chat_ids.includes(data[i]['chat_id'])){
                        //append data in unread section or historical section
                        if(data[i]['status'] === 1){
                            $(".unread").append("<div id="+ data[i]['chat_id']+">"+
                                "<p>"+ "sender id: " + data[i]['sender_id'] +"</p>"+
                                "<p>"+ "content: "+ data[i]['chat'] +"</p>"+
                                "<p>"+ "time: " + unix_timestamps_js(data[i]['timestamps']) +"</p>"+
                                "<p>----------------------------------------------------</p>"+
                                "</div>");
                            //record read messages id and will be called in the following ajax function.
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
                //return the read messages id
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
