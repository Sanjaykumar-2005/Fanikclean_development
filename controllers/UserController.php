<?php
require_once __DIR__ . '/../models/User.php';

class UserController extends Controller {
    public function __construct() {
        $this->requireRole([1]);
    }

    public function index() {
        $userModel = new User();
        $users = $userModel->getAll();
        
        $db = Database::connect();
        $sites = $db->query("SELECT id, name FROM sites ORDER BY name")->fetchAll();
        $roles = $db->query("SELECT id, name FROM roles ORDER BY id")->fetchAll();

        $this->view('users/index', [
            'users' => $users,
            'sites' => $sites,
            'roles' => $roles
        ]);
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['full_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $roleId = $_POST['role_id'];
            
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            $userModel = new User();
            // Check if user exists
            if ($userModel->findByEmail($email)) {
                $_SESSION['error'] = "Email already registered";
            } else {
                if ($userModel->create($name, $email, $passwordHash, $roleId)) {
                    $_SESSION['toast'] = "User created successfully";
                }
            }
            $this->redirect('/users');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'full_name' => $_POST['full_name'],
                'role_id' => $_POST['role_id'],
                'site_id' => $_POST['site_id'],
                'status' => $_POST['status']
            ];
            
            $userModel = new User();
            if ($userModel->update($id, $data)) {
                $_SESSION['toast'] = "User updated successfully";
            }
            $this->redirect('/users');
        }
    }
}
