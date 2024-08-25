<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;

/**
 * @OA\Info(title="Documentation API Library", version="1.0")
 */
abstract class Controller
{
    use ApiResponser;

    protected function logError(\Throwable $th, string $context) : void {
        logger()->error($context, [
            'Message ' => $th->getMessage(),
            'On File ' => $th->getFile(),
            'On Line ' => $th->getLine()
        ]);
    }
}
