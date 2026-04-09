<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        require_once "views/layout/header.php";
        require_once "views/layout/sidebar.php";
        require_once "views/$view.php";
        require_once "views/layout/footer.php";
    }

    public function viewAuth($view, $data = []) {
        extract($data);
        require_once "views/$view.php";
    }

    public function redirect($url) {
        header("Location: $url");
        exit;
    }

    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }
}
