<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Rent;
use App\Models\RentItem;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarangs = Barang::count();
        $totalRenter = User::where('role', 'renter')->count();
        $barangRenteds = Rent::whereNull('actual_return_date')->get()->sum(function ($rent) {
            return $rent->rentItems->count();
        });
        $lostBarangs = RentItem::where('is_lost', true)->count();

        $data = Rent::latest()->take(5)->get();

        return view('backend.dashboard', [
            'totalBarangs' => $totalBarangs,
            'totalRenter' => $totalRenter,
            'barangRenteds' => $barangRenteds,
            'lostBarangs' => $lostBarangs,
            'data' => $data
        ]);
    }
}
