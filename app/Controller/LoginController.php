<?php
namespace App\Controller;

use App\Model\Eloquent\UserEloquent;
use Base\AbstractController;
use Base\RedirectException;

class LoginController extends AbstractController
{
    /**
     * @throws RedirectException
     */
    public function index()
    {
        if ($this->getUser()) {
            $this->redirect('/blog');
        }
        return $this->view->render(
                'login.phtml',
            [
                'title' => 'Главная',
                'user' => $this->getUser(),
            ]
        );
    }

    public function auth()
    {
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        $user = UserEloquent::getByEmail($email);
        if(!$user) {
            return 'Неверный логин и пароль';
        }

        if ($user->getPassword() !== UserEloquent::getPasswordHash($password)) {
            return 'Неверный логин и пароль';
        }

        $this->session->authUser($user->getId());

        //echo 'Вы успешно авторизовались';
        $this->redirect('/blog');
    }

    public function register()
    {
        $name = (string) $_POST['name'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];
        $password2 = (string) $_POST['password_2'];

        if (!$name || !$password) {
            return 'Не заданы имя и пароль';
        }

        if (!$email) {
            return 'Не задан email';
        }

        if ($password !== $password2){
            return 'Введенные пароли не совпадают';
        }

        if (mb_strlen($password) < 5) {
            return 'Пароль слишком короткий';
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $user = new UserEloquent($userData);
        $user->save();

        $this->session->authUser($user->getId());

        $this->redirect('/blog');
    }

    public function logout()
    {
        $this->session->dropSession();
        $this->redirect('/');
    }
}
