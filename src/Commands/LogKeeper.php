<?php

namespace FjAhmed\LaravelLogKeeper\Commands;

use Exception;
use Illuminate\Console\Command;
use FjAhmed\LaravelLogKeeper\Factories\LogKeeperServiceFactory;

class LogKeeper extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laravel-log-keeper';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload local logs, delete old logs both locally and remote';

    private $config;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logger = \Log::getLogger();

        try {
            $service = LogKeeperServiceFactory::buildFromLaravelConfig();
            $logger  = $service->getLogger();
            $service->work();
        } catch (Exception $e) {
            $logger->error("Something went wrong: {$e->getMessage()}");
        }
    }
}
