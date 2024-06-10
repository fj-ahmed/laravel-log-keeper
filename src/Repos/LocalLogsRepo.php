<?php

namespace FjAhmed\LaravelLogKeeper\Repos;

use Exception;
use Illuminate\Filesystem\Filesystem;
use FjAhmed\LaravelLogKeeper\Support\LogUtil;

class LocalLogsRepo implements LogsRepoInterface
{
    private $config;
    private $localLogPath;
    private $disk;

    public function __construct(array $config)
    {
        $this->config       = $config;
        $this->localLogPath = storage_path('logs');
        $this->disk         = new Filesystem();
    }

    public function getLogs()
    {
        $allLogs = $this->disk->files($this->localLogPath);
        $logs    = LogUtil::getLogs($allLogs);
        $logs    = LogUtil::mapBasename($logs);

        return $logs;
    }

    public function getCompressed()
    {
        $allLogs = $this->disk->files($this->localLogPath);
        $logs = LogUtil::getCompressed($allLogs);
        $logs = LogUtil::mapBasename($logs);

        return $logs;
    }

    public function delete($log)
    {
        $path = "{$this->localLogPath}/{$log}";

        $this->disk->delete($path);
    }

    public function compress($log, $compressedName)
    {
//        $command = "cd {$this->localLogPath}; tar cjf {$compressedName} {$log}";
//
//        execCmd($command, $output, $exit);
//
//        if ($exit) {
//            throw new Exception("Something went wrong when compressing {$log}");
//        }

        $zipper = new \Madnest\Madzipper\Madzipper;


        $zipper->make(storage_path("logs/{$compressedName}"))->add(storage_path("logs/{$log}"));
        $zipper->close();

        $this->disk->delete("{$this->localLogPath}/{$log}");
    }

    public function get($log)
    {
        $path = "{$this->localLogPath}/{$log}";
        return $this->disk->get($path);
    }

    public function put($log, $content)
    {
        $this->disk->put($log, $content);
    }
}
