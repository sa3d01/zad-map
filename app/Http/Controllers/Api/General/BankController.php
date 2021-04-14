<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\BankCollection;
use App\Models\Bank;
use App\Models\User;

class BankController extends MasterController
{
    protected $model;

    public function __construct(Bank $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $admins = User::where('type', 'ADMIN')->pluck('id');
        $banks = Bank::whereIn('user_id', $admins)->get();
        return $this->sendResponse(new BankCollection($banks));
    }

}
