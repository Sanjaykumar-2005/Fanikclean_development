<?php
require_once __DIR__ . '/../models/User.php';

class UserController extends Controller {
    public function __construct() {
        $this->checkAuth();
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }
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
