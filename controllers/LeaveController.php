<?php
class LeaveController extends Controller {
    public function __construct() { $this->checkAuth(); }
    public function index() {
        // Will render leave module if needed, using a blank mockup for now
        $this->view('leave/index', ['pageTitle' => 'Leave Management']);
    }
}
