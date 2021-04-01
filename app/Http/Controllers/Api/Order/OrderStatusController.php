<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Order\CancelOrderRequest;
use App\Models\CancelOrder;
use App\Models\Order;

class OrderStatusController extends MasterController
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function cancelOrder($id, CancelOrderRequest $request): object
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (($order->user_id != auth('api')->id() && $order->provider_id != auth('api')->id()) || ($order->status == 'rejected' || $order->status == 'completed')) {
            return $this->sendError("ﻻ يمكنك الغاء هذا الطلب");
        }
        $order->update([
           'status'=>'rejected'
        ]);
        CancelOrder::create([
            'user_id' => auth('api')->id(),
            'order_id' => $id,
            'reason' => $request['reason']
        ]);
        //todo:notify
        return $this->sendResponse([], 'تم الغاء الطلب بنجاح');
    }

    public function acceptOrder($id): object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if ($order->provider_id != auth('api')->id() || $order->status != 'new') {
            return $this->sendError("ﻻ يمكنك قبول هذا الطلب");
        }
        $order->update([
           'status'=>'pre_paid'
        ]);
        //todo:notify
        return $this->sendResponse([], 'تم قبول الطلب بنجاح');
    }
}
