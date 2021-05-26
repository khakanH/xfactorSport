<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\CustomException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->renderable(function (Exception $e, $request) {

            if ($this->isHttpException($e)) 
            {
                if ($e->getStatusCode() == 419) 
                {
                    return redirect()->route('index');
                }
                elseif ($e->getStatusCode() == 404) 
                {
                    
                }

            }
                
        });
    }

}
