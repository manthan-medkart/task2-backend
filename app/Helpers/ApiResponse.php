<?php

namespace App\Helpers;

trait ApiResponse
{
    public function success(string $message,int $statusCode,mixed $data)
    {
        switch($statusCode){
            case 200 : return $this->successResponse($message,$data,$statusCode);
            case 201 : return $this->createdResponse($message,$data,$statusCode);
            default : return $this->serverErrorResponse($message,$data,$statusCode);
        }
    }
    public function error(string $message,int $statusCode,mixed $error,)
    {
        switch($statusCode){
            case 400 : return $this->badRequestResponse($message, $error, $statusCode);
            case 401 : return $this->unauthorizedResponse($message, $error, $statusCode);
            case 403 : return $this->forbiddenResponse($message,$error, $statusCode);
            case 404 : return $this->notFoundResponse($message, $error, $statusCode);
            default : return $this->serverErrorResponse($message, $error, $statusCode);
        }
    }

    public function successResponse(string $message,mixed $statusCode,mixed $data){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }

    public function createdResponse(string $message,mixed $data,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }

    public function acceptedResponse(string $message,mixed $data,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }
    public function serverErrorResponse(string $message,mixed $data,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }

    public function badRequestResponse(string $message,mixed $error,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }

    public function notFoundResponse(string $message,mixed $error,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }
    public function forbiddenResponse(string $message,mixed $error,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }
    public function unAuthorizedResponse(string $message,mixed $error,int $statusCode){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }
}
