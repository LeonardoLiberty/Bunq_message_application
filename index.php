<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require __DIR__ . '/./vendor/autoload.php';
include 'SQLite_connection.php';

$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello World');
    return $response;
});


$app->post('/api', function ($request, $response, $args) {
    $data = $request->getQueryParams();

    $sender = $data['sender'];
    $recipient = $data['recipient'];
    $msg = $data['msg'];
    $status = intval($data['status']);
    $timestamps = strval(time());

    insert_msg($sender, $recipient, $msg, $status, $timestamps);

    $data2 = array('name' => 'Rob', 'mmmassage' => '`1111111');

    $payload = json_encode($data2);

    $response->getBody()->write($payload);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);

});




$app->run();

function insert_msg($sender, $recipient, $msg, $status, $timestamps) {
    if($status == 1){
        $status = 2;
    }

    $db = new MyDB();
//    $sql = 'INSERT OR IGNORE INTO chats (sender_id, recipient_id, chat, status, timestamps) VALUES ("'.$sender.'", "'.$recipient.'", "'.$msg.'", "'.$status.'", "'.$timestamps.'")';
//    error_log(print_r($sql, true));
//    if($db){
//        $ret = $db->exec($sql);
//        if(!$ret) {
//            echo $db->lastErrorMsg();
//        } else {
//            echo "chat added successfully\n";
//        }
//    }

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
            $status = 1;
        } else {
            echo "chat added successfully\n";
        }

    }

}




