<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = User::query();

        if ($search) {
            $data->where('name', 'like', '%' . $search . '%');
        }

        $data = $data->where('role', 'admin')->latest()->paginate(10);

        return view('backend.admin.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('backend.admin.create');
    }

    public function store(AdminRequest $request)
    {
        $data = $request->validated();

        $data['role'] = 'admin';

        User::create($data);

        return redirect()->route('panel.admins.index')->with('success', 'Admin <b>' . $data['name'] . '</b> berhasil ditambahkan');

    }

    public function edit(User $admin)
    {
        return view('backend.admin.edit', [
            'admin' => $admin
        ]);
    }

    public function update(AdminRequest $request, User $admin)
    {
        $data = $request->validated();

        if ($request->password === null) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $admin->update($data);

        return redirect()->route('panel.admins.index')->with('success', 'Admin <b>' . $admin->name . '</b> berhasil diubah');
    }

    public function destroy(User $admin)
    {
        $admin->delete();

        return redirect()->back()->with('success', 'Admin <b>' . $admin->name . '</b> berhasil dihapus');
    }
}
