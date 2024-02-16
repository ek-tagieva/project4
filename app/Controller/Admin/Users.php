<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Model\Eloquent\UserEloquent;

class Users extends AdminController
{
    public function index()
    {
        return $this->view->render(
            'admin/users.phtml',
            [
                'users' => UserEloquent::getList()
            ]
        );
    }

    public function saveUser()
    {
            $id = (int) $_POST['id'];
            $name = (string) $_POST['name'];
            $email = (string) $_POST['email'];
            $password = (string) $_POST['password'];

            $user = UserEloquent::getById($id);
            if (!$user) {
                return $this->response(['error' => 'no_user']);
            }

            if (!$name) {
                return $this->response(['error' => 'no_name']);
            }

            if (!$email) {
                return $this->response(['error' => 'no_email']);
            }

            if ($password && mb_strlen($password) < 5) {
                return $this->response(['error' => 'too short password']);
            }

            $user->name = $name;
            $user->email = $email;

            /** @var UserEloquent $emailUser */
            $emailUser = UserEloquent::getByEmail($email);
            if ($emailUser && $emailUser->id !=$id) {
                return $this->response(['error' => 'this email already userd by uid#' . $emailUser->id]);
            }

            if ($password) {
                $user->password = UserEloquent::getPasswordHash($password);
            }
            $user->save();

            return $this->response(['result' => 'ok']);
    }

    public function deleteUser()
    {
        $id = (int) $_POST['id'];

        $user = UserEloquent::getById($id);
        if (!$user) {
            return $this->response(['error' => 'no_user']);
        }
        $user->delete();
        return $this->response(['result' => 'ok']);
    }

    public function addUser()
    {
        $name = (string) $_POST['name'];
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        if (!$name || !$password) {
            return 'Не заданы имя и пароль';
        }

        if (!$name) {
            return $this->response(['error' => 'no_name']);
        }

        if (!$email) {
            return $this->response(['error' => 'no_email']);
        }

        if (!$password || mb_strlen($password) < 5) {
            return $this->response(['error' => 'too short password']);
        }

        /** @var UserEloquent $emailUser */
        $emailUser = UserEloquent::getByEmail($email);
        if ($emailUser) {
            return $this->response(['error' => 'this email already userd by uid#' . $emailUser->id]);
        }

        $userData = [
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'password' => UserEloquent::getPasswordHash($password),
            'email' => $email,

        ];

        $user = new UserEloquent($userData);
        $user->save();

        return $this->response(['result' => 'ok']);
    }

    public function response(array $data)
    {
        header('Content-type: application.json');
        return json_encode($data);
    }
}