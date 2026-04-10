<?php
require_once __DIR__ . '/../models/Worker.php';

class WorkerController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $workerModel = new Worker();
        $siteId = $this->isAdmin() ? null : $this->getSiteId();
        $workers = $workerModel->getAll($siteId);
        $this->view('workers/index', ['workers' => $workers]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $workerModel = new Worker();
            // Validate required inputs based on name attributes we will inject in view
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'mobile' => $_POST['mobile'] ?? '',
                'aadhaar' => $_POST['aadhaar'] ?? '',
                'doj' => $_POST['doj'] ?? date('Y-m-d'),
                'category_id' => $_POST['category_id'] ?? 4, // 4=Helper default
                'site_id' => !empty($_POST['site_id']) ? $_POST['site_id'] : null,
                'status' => $_POST['status'] ?? 'Active'
            ];
            $workerModel->create($data);
            $_SESSION['toast'] = "Worker saved successfully";
            $this->redirect('/workers');
        }
    }
    public function apiGetBySite() {
        $this->checkAuth();
        header('Content-Type: application/json');
        $site_id = $_GET['site_id'] ?? null;
        if (!$site_id) { echo json_encode([]); return; }
        
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id, full_name, worker_code, category_id FROM workers WHERE site_id = :sid AND status = 'Active'");
        $stmt->execute(['sid' => $site_id]);
        echo json_encode($stmt->fetchAll());
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                $this->redirect('/workers');
                return;
            }

            $workerModel = new Worker();
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'mobile' => $_POST['mobile'] ?? '',
                'aadhaar' => $_POST['aadhaar'] ?? '',
                'doj' => $_POST['doj'] ?? date('Y-m-d'),
                'category_id' => $_POST['category_id'] ?? 4,
                'site_id' => !empty($_POST['site_id']) ? $_POST['site_id'] : null,
                'status' => $_POST['status'] ?? 'Active'
            ];
            
            $workerModel->update($id, $data);
            $_SESSION['toast'] = "Worker updated successfully";
            $this->redirect('/workers');
        }
    }
}

