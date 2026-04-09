<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Worker.php';

class AttendanceController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $workerModel = new Worker();
        $workers = $workerModel->getAll();
        
        $this->view('attendance/index', [
            'pageTitle' => 'Attendance Management',
            'workers' => $workers,
            'date' => $date
        ]);
    }

    public function saveBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'] ?? date('Y-m-d');
            $siteId = $_POST['site_id'] ?? null;
            $attendanceData = $_POST['attendance'] ?? []; // Expected [worker_id => ['status' => 'p', 'ot' => 2]]

            if (!$siteId) {
                $this->redirect('/attendance');
            }

            $attModel = new Attendance();
            $userId = $_SESSION['user_id'];
            
            // Fixed Arguments mapping
            $attModel->saveBulk($siteId, $date, $attendanceData, $userId);
            
            $_SESSION['toast'] = "Attendance saved successfully for $date";
            $this->redirect('/attendance?site_id=' . $siteId . '&date=' . $date);
        }
    }
}
