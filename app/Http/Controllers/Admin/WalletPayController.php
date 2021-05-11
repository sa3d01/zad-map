<?php

namespace App\Http\Controllers\Admin;

use App\Models\WalletPay;

class WalletPayController extends MasterController
{
    public function __construct(WalletPay $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('type', 'transfer')->latest()->get();
        return view('Dashboard.wallet-pay.index', compact('rows'));
    }

    public function reject($id): object
    {
        $pay = $this->model->find($id);
        $pay->update(
            [
                'status' => 'rejected',
            ]
        );
        $pay->refresh();
        return redirect()->back()->with('updated');
    }

    public function accept($id)
    {
        $user = $this->model->find($id);
        $user->update(
            [
                'status' => 'accepted',
            ]
        );
        $user->refresh();
        return redirect()->back()->with('updated');
    }

}
