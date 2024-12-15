<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function managerDashboard (){

        return view ('role.manager.dashboard');
    }
}
