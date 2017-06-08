<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReportDebtService;
use App\Services\ReportPayableService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $debts;
    private $payables;
    public function  __construct(ReportDebtService $debts, ReportPayableService $payables) {
        $this->debts = $debts;
        $this->payables = $payables;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data['date'] = $date = Carbon::today()->addDays(2);
        $data['debts'] = $this->debts->getDataDashboard($date);
        $data['payables'] = $this->payables->getDataDashboard($date);

        return view('pages.dashboard', $data);
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
