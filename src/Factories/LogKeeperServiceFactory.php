<?php
namespace FjAhmed\LaravelLogKeeper\Factories;

use FjAhmed\LaravelLogKeeper\Repos\LocalLogsRepo;
use FjAhmed\LaravelLogKeeper\Repos\RemoteLogsRepo;
use FjAhmed\LaravelLogKeeper\Services\LogKeeperService;
use Monolog\Handler\NullHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class LogKeeperServiceFactory
{
    /**
     * @param array $config
     * @return LogKeeperService
     * @throws \Exception
     */
    public static function buildFromConfig(array $config): LogKeeperService
    {
        $logger = new Logger('laravel-log-keeper');

        if ($config['log']) {
            $logger->pushHandler(new RotatingFileHandler(storage_path('logs') . '/laravel-log-keeper.log', 365, Logger::INFO));
        } else {
            $logger->pushHandler(new NullHandler());
        }

        $localRepo  = new LocalLogsRepo($config);
        $remoteRepo = new RemoteLogsRepo($config);
        return new LogKeeperService($config, $localRepo, $remoteRepo, $logger);
    }

    /**
     * @throws \Exception
     */
    public static function buildFromLaravelConfig(): LogKeeperService
    {
        $config = config('laravel-log-keeper');
        return static::buildFromConfig($config);
    }
}
