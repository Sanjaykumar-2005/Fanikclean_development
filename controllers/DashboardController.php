<?php

class DashboardController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $this->view('dashboard/index', ['pageTitle' => 'Dashboard']);
    }
}
