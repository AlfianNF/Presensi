<?php

namespace App\Http\Controllers;

use App\Models\SettingPresensi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function user()
    {
        $users = User::paginate(20);
        return view('dashboard.user', compact('users'));
    }

    public function setting()
    {
        $settings = SettingPresensi::with('user')->get();
        return view('dashboard.setting', compact('settings'));
    }

    public function profil()
    {
        return view('dashboard.profil');
    }

}
