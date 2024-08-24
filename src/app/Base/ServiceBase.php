<?php

namespace App\Base;

use App\Contracts\ServiceContract;
use App\Responses\ServiceResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class ServiceBase implements ServiceContract {

    /**
     * To return success response of the service
     *
     * @param $data
     * @param string $message
     * @param string|null $customCode
     * @return ServiceResponse
     */
    protected static function success($data, string $message = "success", string $customCode = null): ServiceResponse {
        return new ServiceResponse($data, $message, 200, $customCode);
    }

    /**
     * To return error response of the service
     *
     * @param $data
     * @param string $message
     * @param int $status
     * @param string|null $customCode
     * @return ServiceResponse
     */
    protected static function error($data, string $message = "error", int $status = 400, string $customCode = null): ServiceResponse {
        return new ServiceResponse($data, $message, $status, $customCode);
    }

    /**
     * To return error response of the service
     *
     * @param Throwable $throwable
     * @param string $message
     * @param int $status
     * @param string|null $customCode
     * @return ServiceResponse
     */
    protected static function catchError(Throwable $throwable, string $message = "service error", int $status = 400, string $customCode = null): ServiceResponse {
        Log::error("Service error",[
            "message" => $throwable->getMessage(),
            "file"    => $throwable->getFile(),
            "line"    => $throwable->getLine(),
        ]);
        return new ServiceResponse(null, $message, $status, $customCode);
    }

}
