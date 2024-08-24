<?php

namespace App\Traits;

trait ApiResponser {

    protected function success($data, $message = null, $method = null, $code = 200, string $customCode = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'	=> true,
            'code'      => $customCode ?? 200,
            'text'      => $message,
            'method' 	=> $method,
            'data' 	    => $data
        ], $code);
    }

    protected function error($message = null, $method = null, $code = 400, $data = null, string $customCode = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'	=> false,
            'code'      => $customCode ?? 400,
            'text' 	    => $message,
            'method' 	=> $method,
            'data'  	=> $data,
        ], $code);
    }

}
