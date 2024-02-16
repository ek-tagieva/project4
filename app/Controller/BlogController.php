<?php
namespace App\Controller;

use App\Model\Eloquent\MessageEloquent;
use Base\AbstractController;

class BlogController extends AbstractController
{
    public function index()
    {
        if (!$this->getUser()) {
            $this->redirect('/login');
        }
        $messages = MessageEloquent::getList();

        return $this->view->render('blog.phtml', [
            'messages' => $messages,
            'user' => $this->getUser()
        ]);
    }

    public function addMessage()
    {
        if (!$this->getUser()) {
            $this->redirect('/login');
        }

        $text = (string) $_POST['text'];
        if (!$text) {
            $this->error('Сообщение не может быть пустым');
        }

        $message = new MessageEloquent([
            'text' => $text,
            'author_id' => $this->getUserId(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if (isset($_FILES['image']['tmp_name'])) {
            $message->loadFile($_FILES['image']['tmp_name']);
        }

        $message->save();
        $this->redirect('/blog');
    }

    public function twig()
    {
        return $this->view->renderTwig('test.twig', ['var' => 'lalala']);
    }

    private function error()
    {

    }
}