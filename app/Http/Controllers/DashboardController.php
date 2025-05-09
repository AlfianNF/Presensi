<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function user()
    {
        $users = User::paginate(20);
        return view('dashboard.user', compact('users'));
    }
}
