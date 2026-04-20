<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/ManagerAttendance.php';
require_once __DIR__ . '/../models/User.php';

class AttendanceController extends Controller {
    
    public function index() {
        $this->checkAuth();
        $date = $_GET['date'] ?? date('Y-m-d');
        $siteId = $_GET['site_id'] ?? null;
        
        $attModel = new Attendance();
        $workers = [];
        // Workers are loaded via AJAX API now, but we prepare the view
        
        $db = Database::connect();
        $sites = $this->isAdmin() ? 
                 $db->query("SELECT id, name FROM sites ORDER BY name")->fetchAll() : 
                 $db->query("SELECT id, name FROM sites WHERE id IN (".implode(',',$this->getAssignedSiteIds()).") ORDER BY name")->fetchAll();

        $this->view('attendance/index', [
            'pageTitle' => 'Worker Attendance',
            'workers' => $workers,
            'sites' => $sites,
            'date' => $date,
            'selectedSiteId' => $siteId
        ]);
    }

    public function managerAttendance() {
        $this->requireRole([1]);
        $date = $_GET['date'] ?? date('Y-m-d');
        $monthYear = date('Y-m', strtotime($date));
        
        $userModel = new User();
        $managers = $userModel->getManagers();
        
        $maModel = new ManagerAttendance();
        // Fetch specific day history
        $history = $maModel->getByMonth($monthYear); 
        $plCounts = $maModel->getMonthlyPLCounts($monthYear);

        $this->view('attendance/manager', [
            'pageTitle' => 'Daily Manager Attendance',
            'managers' => $managers,
            'history' => $history,
            'date' => $date,
            'month' => $monthYear,
            'plCounts' => $plCounts
        ]);
    }

    public function viewMyAttendance() {
        $this->requireRole([2]); // Manager only staff
        
        $monthYear = $_GET['month'] ?? date('Y-m');
        $maModel = new ManagerAttendance();
        $history = $maModel->getByUser($_SESSION['user_id'], $monthYear);

        $this->view('attendance/my_attendance', [
            'pageTitle' => 'My Attendance History',
            'history' => $history,
            'month' => $monthYear
        ]);
    }

    public function saveBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'] ?? date('Y-m-d');
            $siteId = $_POST['site_id'] ?? null;
            $attendanceData = $_POST['attendance'] ?? [];

            if ($siteId && !$this->canAccessSite($siteId)) {
                $_SESSION['error'] = "Unauthorized site access";
                $this->redirect('/attendance');
                return;
            }

            if (!$siteId) { $this->redirect('/attendance'); return; }

            $attModel = new Attendance();
            $userId = $_SESSION['user_id'];
            $attModel->saveBulk($siteId, $date, $attendanceData, $userId);
            
            $_SESSION['toast'] = "Attendance saved for " . date('d M', strtotime($date));
            $this->redirect('/attendance?site_id=' . $siteId . '&date=' . $date);
        }
    }

    public function saveManagerAttendance() {
        $this->requireRole([1]);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'] ?? date('Y-m-d');
            $month = date('Y-m', strtotime($date));
            $attendanceData = $_POST['manager_attendance'] ?? []; // [uid => [status, note]]
            
            // Format for saveBulk: [ 'YYYY-MM-DD' => [ uid => [status, note] ] ]
            $formattedRecords = [
                $date => $attendanceData
            ];
            
            $maModel = new ManagerAttendance();
            if ($maModel->saveBulk($month, $formattedRecords, $_SESSION['user_id'])) {
                $_SESSION['toast'] = "Manager attendance saved for " . date('d M', strtotime($date));
            } else {
                $_SESSION['error'] = "Failed to update internal records";
            }
            $this->redirect('/attendance/manager?date=' . $date);
        }
    }
}
