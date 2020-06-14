<?php

namespace noone;

class Log
{

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function log(string $name, $data)
    {
        $logname = "{$name}.log";
        $destination = $this->app->getRuntimePath() . $logname;
        $path = dirname($destination);
        !is_dir($path) && mkdir($path, 0755, true);

        if (is_object($data) || is_array($data)) {
            $data = var_export($data, true);
        }
        $data = sprintf("[%s] %s\r\n",date('Ymd H:i:s'),$data);
        file_put_contents($destination, $data, FILE_APPEND);
    }
}
