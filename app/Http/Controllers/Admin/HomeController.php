<?php

namespace App\Http\Controllers\Admin;

class HomeController extends MasterController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('Dashboard.index');
    }
}
