<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Worker.php';

class AttendanceController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $siteId = $this->isAdmin() ? ($_GET['site_id'] ?? null) : $this->getSiteId();
        
        $workerModel = new Worker();
        $workers = $workerModel->getAll($siteId);
        
        $this->view('attendance/index', [
            'pageTitle' => 'Attendance Management',
            'workers' => $workers,
            'date' => $date,
            'selectedSiteId' => $siteId
        ]);
    }

    public function saveBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'] ?? date('Y-m-d');
            $siteId = $_POST['site_id'] ?? null;
            $attendanceData = $_POST['attendance'] ?? [];

            // Authorization check
            if (!$this->isAdmin()) {
                if ($siteId != $this->getSiteId()) {
                    $_SESSION['error'] = "Unauthorized site access";
                    $this->redirect('/attendance');
                    return;
                }
            }

            if (!$siteId) {
                $this->redirect('/attendance');
                return;
            }

            $attModel = new Attendance();
            $userId = $_SESSION['user_id'];
            $attModel->saveBulk($siteId, $date, $attendanceData, $userId);
            
            $_SESSION['toast'] = "Attendance saved successfully for $date";
            $this->redirect('/attendance?site_id=' . $siteId . '&date=' . $date);
        }
    }
}
