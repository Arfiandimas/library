<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;

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
