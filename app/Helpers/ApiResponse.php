<?php

namespace App\Helpers;

trait ApiResponse
{
    public function success(string $message,int $statusCode,mixed $data)
    {
        switch($statusCode){
            case 200 : return $this->successResponse($message,$statusCode,$data);
            case 201 : return $this->createdResponse($message,$statusCode,$data);
            default : return $this->serverErrorResponse($message,$statusCode,$data);
        }
    }
    public function error(string $message,int $statusCode,mixed $error,)
    {
        switch($statusCode){
            case 400 : return $this->badRequestResponse($message, $statusCode, $error);
            case 401 : return $this->unauthorizedResponse($message, $statusCode, $error);
            case 403 : return $this->forbiddenResponse($message, $statusCode,$error);
            case 404 : return $this->notFoundResponse($message, $statusCode, $error);
            default : return $this->serverErrorResponse($message, $statusCode, $error);
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

    public function createdResponse(string $message,int $statusCode,mixed $data){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }

    public function acceptedResponse(string $message,int $statusCode,mixed $data){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }
    public function serverErrorResponse(string $message,int $statusCode,mixed $data){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return $this->response($response,$statusCode);
    }

    public function badRequestResponse(string $message,int $statusCode,mixed $error){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }

    public function notFoundResponse(string $message,int $statusCode,mixed $error){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }
    public function forbiddenResponse(string $message,int $statusCode,mixed $error){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }
    public function unAuthorizedResponse(string $message,int $statusCode,mixed $error){
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
            'error' => $error,
        ];
        return $this->response($response,$statusCode);
    }
}
