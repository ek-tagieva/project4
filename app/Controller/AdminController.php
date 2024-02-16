<?php
namespace App\Controller;

use App\Model\Eloquent\MessageEloquent;
use Base\AbstractController;

class AdminController extends AbstractController
{
    public function preDispatch()
    {
        parent::preDispatch();
        if (!$this->getUser() || !$this->getUser()->isAdmin()) {
            $this->redirect('/');
        }
    }

    public function deleteMessage()
    {
        $messageId = (int) $_GET['id'];
        MessageEloquent::deleteMessage($messageId);
        $this->redirect('/blog');
    }
}