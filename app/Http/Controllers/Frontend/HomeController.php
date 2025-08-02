<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularBarangs = Barang::withCount('rentItems')
            ->orderBy('rent_items_count', 'desc')
            ->take(10)
            ->get();

        return view('frontend.home', [
            'barangs' => Barang::latest()->paginate(12),
            'popularBarangs' => $popularBarangs,
        ]);
    }
}
