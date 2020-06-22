<?php

namespace noone;

use ErrorException;
use Throwable;

class Exceptions
{


    protected App $app;

    public function bootstrap(App $app)
    {
        $this->app = $app;
        error_reporting(-1);
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutDown']);
    }


    /**
     * 错误转成异常处理
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     * @return void
     * @author xsm
     * @since 2020-06-09
     */
    public function handleError(int $errno, string $errstr, string $errfile, int $errline)
    {

        if (error_reporting() & $errno) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }

    public function handleException(Throwable $ex)
    {
        $this->getHandler()->report($ex);
        if ($this->app->isDebug()) {
            $this->getHandler()->render($ex);
        }
        // print_r($ex->getMessage());
    }

    public function handleShutDown()
    {
        // echo 'showdown';
    }

    public function getHandler()
    {
        return $this->app->make(ExceptionsHanlder::class);
    }
}
