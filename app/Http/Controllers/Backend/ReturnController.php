<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\RentItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $code = $request->query('code');

        $data = null;

        $settings = View::shared('settings');

        if ($code) {
            $data = Rent::where('code', $code)->first();

            if (Carbon::today() > $data->return_date) {
                $late_fee = $settings['late_fee_per_day'];
                $diff = ceil($data->return_date->diffInDays(Carbon::today()));
                $data->pinalty = $diff * $late_fee * $data->rentItems->count();
            }

            if ($data->lost_books > 0) {
                $lost_fee = $settings['lost_fee'];
                $data->pinalty += $data->lost_books * $lost_fee;
            }
        }

        return view('backend.return.index', [
            'renting' => $data
        ]);
    }

    public function update(Request $request, string $code)
    {
        $data = $request->validate([
            'pinalty' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $rent = Rent::where('code', $code)->first();

        if ($data['status']) {
            $rent->actual_return_date = Carbon::today();
            $rent->pinalty = $data['pinalty'];
        } else {
            $rent->actual_return_date = null;
            $rent->pinalty = 0;
        }

        $rent->save();

        return redirect()->back()->with('success', 'Data peminjaman berhasil di update');
    }

    public function updateLost(Request $request, string $id)
    {
        $data = $request->validate([
            'is_lost' => 'required|boolean'
        ]);

        $rentItem = RentItem::find($id);
        $rentItem->is_lost = $data['is_lost'];
        $rentItem->save();

        $status = $data['is_lost'] > 0 ? 'Hilang' : 'Tidak Hilang';

        return redirect()->back()->with('success', 'Status buku <b>' . $rentItem->book->title . '</b> berhasil di update menjadi <b>' . $status . '</b>');
    }
}
