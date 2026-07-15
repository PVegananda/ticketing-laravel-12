<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalEvents = Event::count();
        $totalCategories = Kategori::count();
        $totalUsers = User::count();

        return view('pages.admin.dashboard', compact('totalEvents', 'totalCategories', 'totalUsers'));
    }
}