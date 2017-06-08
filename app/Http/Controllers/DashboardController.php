<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('pages.dashboard');
    }

    public function profile() {
        return view('pages.profile');
    }

    public function updateProfile(Request $request) {
        if (Auth::attempt(['email' => auth()->user()->email, 'password' => $request->password_old])) {
            $user = User::find(auth()->id());
            $user->password = $request->password_new;
            $user->save();
            return redirect('profile')->with('success', 'password telah diperbarui');
        }

        return redirect('profile')->with('error', 'password lama tidak cocok');
    }
}
