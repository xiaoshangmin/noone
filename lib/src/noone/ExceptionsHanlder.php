<?php

namespace noone;

use Throwable;

class ExceptionsHanlder
{

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function report(Throwable $exception): void
    {

        $data = [
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
        ];
        $log = "[{$data['code']}]{$data['message']}[{$data['file']}:{$data['line']}]";

        try {
            $this->app->log->log(date('Ymd'), $log);
        } catch (Exception $e) {
        }
    }



    // public function render($request, Throwable $e): Response
    // {
    //     $this->isJson = $request->isJson();
    //     if ($e instanceof HttpResponseException) {
    //         return $e->getResponse();
    //     } elseif ($e instanceof HttpException) {
    //         return $this->renderHttpException($e);
    //     } else {
    //         return $this->convertExceptionToResponse($e);
    //     }
    // }
}
