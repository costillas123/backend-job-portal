<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class InsertSeeder extends Seeder
{
    public function run(): void
    {
        $zipPath = database_path('seeders/data/datas.zip');
        $extractDir = database_path('seeders/data');

        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            $this->command->error("Unable to open zip file: {$zipPath}");

            return;
        }

        $sqlFile = $zip->getNameIndex(0);
        $zip->extractTo($extractDir);
        $zip->close();

        $sqlPath = $extractDir.'/'.$sqlFile;
        $connection = config('database.connections.mysql');

        $this->command->info("Importing {$sqlFile} into '{$connection['database']}'...");

        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s --skip-ssl %s < %s',
            escapeshellarg($connection['host']),
            escapeshellarg((string) $connection['port']),
            escapeshellarg($connection['username']),
            escapeshellarg($connection['password']),
            escapeshellarg($connection['database']),
            escapeshellarg($sqlPath)
        );

        $result = Process::timeout(0)->run($command);

        unlink($sqlPath);

        if ($result->failed()) {
            $this->command->error('Import failed: '.$result->errorOutput());

            return;
        }

        $this->command->info('Database import completed successfully.');
    }
}
