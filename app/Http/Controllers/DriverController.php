<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function driverDashboard(){

        return view('role.driver.dashboard');
        
    }
}
