<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\ZipService;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database (save to .sql.zip file)';

    public function __construct(protected ZipService $zipService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info($this->signature . ' START...');

        $dbConnection = config('database.default');

        if ($dbConnection !== 'pgsql') {
            $message = "Database backup failed: unsupported connection [$dbConnection]. Only 'pgsql' is supported.";
            $this->error($message);
            Log::error($message);
            return Command::FAILURE;
        }

        $backupDir = storage_path('app/backups');
        File::ensureDirectoryExists($backupDir);

        $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = "{$backupDir}/{$fileName}";

        $command = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -p %s -U %s %s > %s 2>&1',
            escapeshellarg(config('database.connections.pgsql.password')),
            escapeshellarg(config('database.connections.pgsql.host')),
            escapeshellarg(config('database.connections.pgsql.port')),
            escapeshellarg(config('database.connections.pgsql.username')),
            escapeshellarg(config('database.connections.pgsql.database')),
            escapeshellarg($path)
        );

        $result = null;
        $output = [];
        exec($command, $output, $result);

        if ($result !== 0) {
            $errorMessage = implode("\n", $output);
            $message = 'DB backup failed: ' . $errorMessage;
            $this->error('DB backup failed: ' . $message);
            Log::error('DB backup failed: ' . $message);
            return Command::FAILURE;
        }

        $zipPath = $this->zipService->zipFile($path, true);

        if ($zipPath) {
            $message = "Backup compressed to: {$zipPath}";
            $this->info($message);
            Log::info($message);
        } else {
            $message = "ZIP compression failed. DB backup saved to: {$path}";
            $this->error($message);
            Log::error($message);
        }

        return Command::SUCCESS;
    }
}
