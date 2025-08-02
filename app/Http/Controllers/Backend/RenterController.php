<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RenterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $data = User::query();

        if ($search) {
            $data->where('name', 'like', '%' . $search . '%');
        }

        if ($status != null) {
            $data->where('is_active', $status);
        }

        $data = $data->where('role', 'renter')->latest()->paginate(10);

        return view('backend.renter.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('backend.renter.create');
    }

    public function show(User $renter)
    {
        return view('backend.renter.show', [
            'renter' => $renter
        ]);
    }

    public function update(Request $request, User $renter)
    {
        $renter->update([
            'is_active' => !$renter->is_active
        ]);

        return redirect()->back()->with('success', 'Status penyewa <b>' . $renter->name . '</b> berhasil diubah');
    }

    public function destroy(User $renter)
    {
        $renter->delete();

        return redirect()->back()->with('success', 'Penyewa <b>' . $renter->name . '</b> berhasil dihapus');
    }
}
