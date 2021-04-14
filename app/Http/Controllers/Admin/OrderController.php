<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;

class OrderController extends MasterController
{
    public function __construct(Order $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function getStatusArabic($status):string
    {
        if ($status=='new'){
            return "الطلبات الجديدة";
        }elseif ($status=='pre_paid'){
            return "الطلبات بانتظار الدفع";
        }elseif ($status=='completed'){
            return "الطلبات المكتملة";
        }elseif ($status=='rejected'){
            return "الطلبات الملغية";
        }else{
            return "الطلبات الجارية";
        }
    }
    public function list($status)
    {
        $rows = $this->model->where('status', $status)->latest()->get();
        $title=$this->getStatusArabic($status);
        return view('Dashboard.order.index', compact('rows','title'));
    }

    public function show($id): object
    {
        $order = $this->model->find($id);
        return view('Dashboard.order.show', compact('order'));
    }

}
