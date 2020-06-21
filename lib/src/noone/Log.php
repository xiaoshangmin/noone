<?php

namespace noone;

class Log
{

    protected App $app;

    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    protected string $logPath = '';

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->bootstrap();
    }

    /**
     * TODO 日志记录方式的初始化（文件、缓存，邮件）
     *
     * @return void
     * @author xsm
     * @since 2020-06-20
     */
    protected function bootstrap()
    {
        $destination = $this->app->getRuntimePath() . "log" . DIRECTORY_SEPARATOR;
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        $this->logPath = $destination;
    }

    public function log(string $level, string $message)
    {
        $message = sprintf("%s %s: %s\r\n", date('Y/m/d H:i:s'), $level, $message);
        $this->write($message);
    }

    public function error(string $message)
    {
        $this->log(self::ERROR, $message);
    }

    public function info(string $message)
    {
        $this->log(self::INFO, $message);
    }

    /**
     * TODO 日志不同记录方式的写入 （文件、缓存，邮件）
     *
     * @param string $message
     * @return void
     * @author xsm
     * @since 2020-06-20
     */
    protected function write(string $message): void
    {
        $logPath = $this->getLogFile();
        file_put_contents($logPath, $message, FILE_APPEND);
    }

    public function getLogFile(): string
    {
        $name = date('Ymd') . '.log';
        $path = $this->logPath . $name;
        return $path;
    }
}
