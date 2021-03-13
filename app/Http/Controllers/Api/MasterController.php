<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

abstract class MasterController extends Controller
{
    protected $model;

    public function __construct()
    {
    }

    public function sendResponse($result, $message = null)
    {
        $response = [
            'status' => 200,
            'message' => $message ? $message : '',
            'data' => $result,
        ];
        return response()->json($response);
    }

    public function sendError($error,$data=[], $code = 400)
    {
        $response = [
            'status' => $code,
            'message' => $error,
            'data' => $data,
        ];
        return response()->json($response, $code);
    }
}
