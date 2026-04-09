<?php
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {

    public function login() {
        $this->viewAuth('auth/login');
    }

    public function signup() {
        $this->viewAuth('auth/signup');
    }

    public function authenticate() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $this->redirect('/dashboard');
        } else {
            $_SESSION['error'] = "Invalid credentials";
            $this->redirect('/login');
        }
    }

    public function register() {
        $name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if ($password !== $confirm) {
            $_SESSION['error'] = "Passwords do not match";
            $this->redirect('/signup');
            return;
        }

        $userModel = new User();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->create($name, $email, $hash);

        $_SESSION['success'] = "Account created. Please log in.";
        $this->redirect('/login');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}
