<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return view('frontend.account');
    }

    public function update(Request $request, User $account)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);

        if ($request->has('phone')) {
            if (substr($data['phone'], 0, 1) === '0') {
                $data['phone'] = '62' . substr($data['phone'], 1);
            } elseif (substr($data['phone'], 0, 1) === '8') {
                $data['phone'] = '62' . $data['phone'];
            }
        }

        $account->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diubah');
    }

    public function updatePassword(Request $request, User $account)
    {
        $data = $request->validate([
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!password_verify($data['old_password'], $account->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai');
        }

        $account->update([
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}
