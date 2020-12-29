<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require __DIR__ . '/./vendor/autoload.php';
include 'src/SQLite_connection.php';

$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello World');
    return $response;
});


$app->post('/api', function ($request, $response, $args) {
    $data = (array)$request->getParsedBody();

    $sender = $data['sender'];
    $recipient = $data['recipient'];
    $msg = $data['msg'];
    $status = intval($data['status']);
    $timestamps = strval(time());
    error_log(print_r($sender, true));

    insert_msg($sender, $recipient, $msg, $status, $timestamps);

    $payload = json_encode($data);

    $response->getBody()->write($payload);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

});

$app->post('/api/fetch', function ($request, $response, $args) {
    $data = (array)$request->getParsedBody();
    $recipient = $data['recipient'];
    $ret = get_msg($recipient);
    $payload = json_encode($ret);

    $response->getBody()->write($payload);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

$app->post('/api/read_msg', function ($request, $response, $args) {
    $data = (array)$request->getParsedBody();
    $chat_id = $data['chat_id'];
    error_log(print_r($chat_id, true));
    read_msg($chat_id);
    $payload = json_encode($chat_id);
    $response->getBody()->write($payload);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});


$app->run();

function read_msg($chat_id){
    if(!$chat_id){
        return;
    }
    $db = new MyDB();
    for($i = 0; $i<count($chat_id); $i++){
        $sql = $db->prepare('UPDATE chats SET status = 2 WHERE chat_id=?');
        $sql->bindParam(1, $chat_id[$i]);
        if($db){
            $ret = $sql->execute();
            if(!$ret) {
                error_log(print_r($db->lastErrorMsg(), true));

            }

        }
    }


}

function get_msg($recipient){
    $db = new MyDB();
    $sql = $db->prepare('SELECT online_status FROM user WHERE user_id=?');
    $sql->bindParam(1, $recipient);
    $online_status = 0;
    if($db){
        $ret = $sql->execute();
        if(!$ret) {
            error_log(print_r($db->lastErrorMsg(), true));

        } else {
            $online_status = $ret->fetchArray(SQLITE3_ASSOC)['online_status'];
            error_log(print_r($online_status, true));
        }

    }
    if($online_status != 1){
        return 0;
    }
    $sql_msg = $db->prepare('SELECT chat_id, sender_id, chat, status, timestamps FROM chats WHERE recipient_id=?');
    $sql_msg->bindParam(1, $recipient);
    $data = array();
    if($db){
        $ret = $sql_msg->execute();
        if(!$ret) {
            error_log(print_r($db->lastErrorMsg(), true));

        } else {
            while($res= $ret->fetchArray(SQLITE3_ASSOC)){
                array_push($data, $res);
            }
            error_log(print_r($data, true));
            $status = $data['chat'];
        }

    }

    return json_encode($data);
}

function insert_msg($sender, $recipient, $msg, $status, $timestamps) {
    if($status == 0){
        $status = 1;
    }

    $db = new MyDB();
    $sql = $db->prepare('INSERT OR IGNORE INTO chats (sender_id, recipient_id, chat, status, timestamps) VALUES (?, ?, ?, ?, ?)');
    $sql->bindParam(1, $sender);
    $sql->bindParam(2, $recipient);
    $sql->bindParam(3, $msg);
    $sql->bindParam(4, $status);
    $sql->bindParam(5, $timestamps);

    if($db){
        $ret = $sql->execute();
        if(!$ret) {
            error_log(print_r($db->lastErrorMsg(), true));
            $status = 0;
        } else {
            echo "chat added successfully\n";
        }

    }

}




