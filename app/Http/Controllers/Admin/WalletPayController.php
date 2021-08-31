<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wallet;
use App\Models\WalletPay;

class WalletPayController extends MasterController
{
    public function __construct(WalletPay $model)
    {
        $this->model = $model;
//        $this->middleware('permission:wallet_pays');
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
        $wallet_pay = $this->model->find($id);
        $wallet_pay->update(
            [
                'status' => 'accepted',
            ]
        );

        $wallet = Wallet::where(['user_id'=> $wallet_pay->user_id,'user_type'=>$wallet_pay->user_type])->latest()->first();
        $new_profits=$wallet->profits + $wallet_pay->amount;
        $new_debtors=0;
        if ($wallet->debtors > $new_profits){
            $new_debtors=$wallet->debtors - $new_profits;
            $new_profits=0;
        }elseif ($wallet->debtors != 0){
            $new_profits=$new_profits - $wallet->debtors;
            $new_debtors=0;
        }
        $wallet->update([
            'profits' => $new_profits,
            'debtors' => $new_debtors,
        ]);

        $wallet_pay->refresh();
        return redirect()->back()->with('updated');
    }

}
