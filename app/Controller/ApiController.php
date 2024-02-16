<?php
namespace App\Controller;

use App\Model\Eloquent\MessageEloquent;
use Base\AbstractController;

class ApiController extends AbstractController
{
    public function getUserMessages()
    {
        $userId = (int) $_GET['user_id'] ?? 0;
        if (!$userId) {
            return $this->response(['error' => 'no_user_id']);
        }
        $messages = MessageEloquent::getUserMessages($userId, 20);
        if (!$messages) {
            return $this->response(['error' => 'no_messages']);
        }
        $data = array_map(function (MessageEloquent $message) {
            return $message->getData();
        }, $messages);

        return $this->response(['messages' => $data]);
    }

    public function response(array $data)
    {
        header('Content-type: application.json');
        return json_encode($data);
    }
}