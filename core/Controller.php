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

    protected function isAdmin() {
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1;
    }

    protected function isManager() {
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2;
    }

    protected function requireRole($allowedRoles) {
        $this->checkAuth();
        if (!in_array($_SESSION['role_id'], $allowedRoles)) {
            $_SESSION['error'] = "Unauthorized access";
            $this->redirect('/dashboard');
        }
    }

    protected function getAssignedSiteIds() {
        return $_SESSION['assigned_site_ids'] ?? [];
    }

    protected function canAccessSite($siteId) {
        if ($this->isAdmin()) return true;
        return in_array($siteId, $this->getAssignedSiteIds());
    }

    protected function getSiteId() {
        // Deprecated: multi-site support uses getAssignedSiteIds()
        return $_SESSION['assigned_site_ids'][0] ?? null;
    }
}
