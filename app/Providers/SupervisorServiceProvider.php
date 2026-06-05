<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class SupervisorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $user = getenv('USER');
        $directory = getenv('PWD'); // หรือ base_path()
        $node = Str::replace('bin/node', 'bin', getenv('NODE'));

        $programs = Storage::build([
            'driver' => 'local',
            'root' => base_path('supervisor/conf.d'),
        ]);

        $logs = Storage::build([
            'driver' => 'local',
            'root' => base_path('supervisor/logs'),
        ]);

        //
        if (!$programs->exists('laravel-reverb.conf') || !$logs->exists('laravel-reverb.log')) {
            # code...
            $this->reverbConfig($programs, $user, $directory, $logs);
        }

        //
        if (!$programs->exists('laravel-pulse.conf') || !$logs->exists('laravel-pulse.log')) {
            # code...
            $this->pulseConfig($programs, $user, $directory, $logs);
        }

        //
        if (!$programs->exists('laravel-horizon.conf') || !$logs->exists('laravel-horizon.log')) {
            # code...
            $this->horizonConfig($programs, $user, $directory, $logs);
        }

        //
        if (!$programs->exists('laravel-queue.conf') || !$logs->exists('laravel-queue.log')) {
            # code...
            $this->queueConfig($programs, $user, $directory, $logs);
        }

        //
        if (!$programs->exists('laravel-schedule.conf') || !$logs->exists('laravel-schedule.log')) {
            # code...
            $this->scheduleConfig($programs, $user, $directory, $logs);
        }

        //
        if (!$programs->exists('laravel-octane.conf') || !$logs->exists('laravel-octane.log')) {
            # code...
            $this->octaneConfig($programs, $user, $directory, $logs);
        }

        //
        if (!$programs->exists('vite-ts.conf') || !$logs->exists('vite-ts.log')) {
            # code...
            $this->viteTsConfig($programs, $user, $directory, $node, $logs);
        }

        //
        if (!$programs->exists('hardhat-viem.conf') || !$logs->exists('hardhat-viem.log')) {
            # code...
            $this->hardhatViemConfig($programs, $user, $directory, $node, $logs);
        }
    }
    
    /**
     * Register the reverb config.
     */
    protected function reverbConfig(Filesystem $programs, string $user, string $directory, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:laravel-reverb]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=php artisan reverb:start
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/laravel-reverb.log
        stopwaitsecs=3600
        CONF;

        $programs->put('laravel-reverb.conf', $config);
        $logs->put('laravel-reverb.log', '');
    }

    /**
     * Register the pulse config.
     */
    protected function pulseConfig(Filesystem $programs, string $user, string $directory, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:laravel-pulse]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=php artisan pulse:check
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/laravel-pulse.log
        stopwaitsecs=3600
        CONF;

        $programs->put('laravel-pulse.conf', $config);
        $logs->put('laravel-pulse.log', '');
    }

    /**
     * Register the horizon config.
     */
    protected function horizonConfig(Filesystem $programs, string $user, string $directory, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:laravel-horizon]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=php artisan horizon
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/laravel-horizon.log
        stopwaitsecs=3600
        CONF;

        $programs->put('laravel-horizon.conf', $config);
        $logs->put('laravel-horizon.log', '');
    }

    /**
     * Register the queue config.
     */
    protected function queueConfig(Filesystem $programs, string $user, string $directory, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:laravel-queue]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=php artisan queue:work
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/laravel-queue.log
        stopwaitsecs=3600
        CONF;

        $programs->put('laravel-queue.conf', $config);
        $logs->put('laravel-queue.log', '');
    }

    /**
     * Register the schedule config.
     */
    protected function scheduleConfig(Filesystem $programs, string $user, string $directory, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:laravel-schedule]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=php artisan schedule:work
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/laravel-schedule.log
        stopwaitsecs=3600
        CONF;

        $programs->put('laravel-schedule.conf', $config);
        $logs->put('laravel-schedule.log', '');
    }

    /**
     * Register the schedule config.
     */
    protected function octaneConfig(Filesystem $programs, string $user, string $directory, Filesystem $logs): void
    {
        $workers = env('PHP_CLI_SERVER_WORKERS') ?? 4;
        $task_workers = env('PHP_CLI_SERVER_TASK_WORKERS') ?? 6;
        $max_requests = env('PHP_CLI_SERVER_MAX_REQUESTS') ?? 250;
        $host = env('SERVER_HOST') ?? trim(shell_exec("hostname -I"));
        $app_port = env('APP_PORT') ?? 8000;
        
        $config = <<<CONF
        [program:laravel-octane]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=php artisan octane:start --workers={$workers} --task-workers={$task_workers} --max-requests={$max_requests} --server=swoole --host={$host} --port={$app_port}
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/laravel-octane.log
        stopwaitsecs=3600
        CONF;

        $programs->put('laravel-octane.conf', $config);
        $logs->put('laravel-octane.log', '');
    }

    /**
     * Register the vite config.
     */
    protected function viteTsConfig(Filesystem $programs, string $user, string $directory, string $node, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:vite-ts]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=npm run build
        environment=PATH="{$node}:/usr/local/bin:/usr/bin:/bin"
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/vite-ts.log
        stopwaitsecs=3600
        CONF;

        $programs->put('vite-ts.conf', $config);
        $logs->put('vite-ts.log', '');
    }

    /**
     * Register the vite config.
     */
    protected function hardhatViemConfig(Filesystem $programs, string $user, string $directory, string $node, Filesystem $logs): void
    {
        $config = <<<CONF
        [program:hardhat-viem]
        process_name=%(program_name)s
        user={$user}
        directory={$directory}
        command=npx hardhat node
        environment=PATH="{$node}:/usr/local/bin:/usr/bin:/bin"
        autostart=false
        autorestart=false
        redirect_stderr=true
        stdout_logfile={$directory}/supervisor/logs/hardhat-viem.log
        stopwaitsecs=3600
        CONF;

        $programs->put('hardhat-viem.conf', $config);
        $logs->put('hardhat-viem.log', '');
    }
}
