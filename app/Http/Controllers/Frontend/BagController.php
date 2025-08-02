<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bag;
use App\Models\Barang;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BagController extends Controller
{
    public function index()
    {
        $popularBarangs = Barang::withCount('rentItems')
            ->orderBy('rent_items_count', 'desc')
            ->take(6)
            ->get();

        $settings = View::shared('settings');

        return view('frontend.bags', [
            'bags' => Auth::user()->bags,
            'popularBarangs' => $popularBarangs,
            'return_date' => Carbon::now()->addDays((int)$settings['max_rent_day'])->format('d-m-Y')
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
        ]);

        $data['user_id'] = Auth::user()->id;

        if (Bag::where('user_id', Auth::user()->id)->where('barang_id', $data['barang_id'])->exists()) {
            return redirect()->route('bags.index')->with('error', 'Barang <b>' . Barang::find($data['barang_id'])->title . '</b> sudah ada di kantong');
        }

        $bag = Bag::create($data);

        return redirect()->route('bags.index')->with('success', 'Barang <b>' . $bag->barang->title . '</b> berhasil ditambahkan ke kantong');
    }

    public function destroy(Bag $bag)
    {
        $bag->delete();

        return redirect()->back()->with('success', 'Barang <b>' . $bag->barang->title . '</b> berhasil dihapus dari kantong');
    }
}
