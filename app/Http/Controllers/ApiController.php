<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;

class ApiController extends Controller
{
    use ApiResponse;

    public function response($data, $statusCode){
        return response()->json($data, $statusCode);
    }
    public function resp($message, $statusCode, $data){
        if($statusCode >= 200 && $statusCode < 400){
            return $this->success($message, $statusCode, $data);
        }
        if($statusCode >= 400 && $statusCode < 500){
            return $this->error($message, $statusCode, $data);
        }
    }
}
