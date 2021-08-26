<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\WalletPayRequest;
use App\Http\Resources\ProviderResourse;
use App\Http\Resources\WalletResource;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletPay;

class ProviderController extends MasterController
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function show($id)
    {
        $provider = User::find($id);
        if (!$provider) {
            return $this->sendError('توجد مشكلة بالبيانات');
        }
        return $this->sendResponse(new ProviderResourse($provider));
    }

    public function wallet()
    {
        $wallet = Wallet::where('user_id', auth('api')->id())->latest()->first();
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => auth('api')->id(),
                'user_type' => \request()->header('userType'),
                'profits' => 0,
                'debtors' => 0
            ]);
        }
        return $this->sendResponse(new WalletResource($wallet));
    }

    public function walletPay(WalletPayRequest $request): object
    {
        $data = $request->validated();
        $data['user_id'] = auth('api')->id();
        $data['user_type'] = \request()->header('userType');
        $data['type'] = 'transfer';
        WalletPay::create($data);
        $wallet = Wallet::where('user_id', auth('api')->id())->latest()->first();
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => auth('api')->id(),
                'user_type' => \request()->header('userType'),
                'profits' => 0,
                'debtors' => 0
            ]);
        }
        $wallet->update([
            'profits' => $wallet->profits + $request['amount'],
        ]);
        Notification::create([
            'receiver_id' => 1,
            'type' => 'admin',
            'title' => 'حوالة بنكية جديدة',
            'note' => 'حوالة بنكية جديدة',
            'more_details' => [
                'type' => 'wallet',
                'user_id' => auth('api')->id()
            ]
        ]);
        return $this->sendResponse(new WalletResource($wallet));
    }


}
