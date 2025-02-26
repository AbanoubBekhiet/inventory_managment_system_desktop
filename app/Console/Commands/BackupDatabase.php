<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\Permission;
use Google\Service\Drive\DriveFile;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup SQLite database to Google Drive';

    public function handle()
    {
        $backupFile = 'database_backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sqlite';
        $localPath = database_path('nativephp.sqlite'); 
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID'); 

        try {
            $client = $this->getGoogleClient();
            $driveService = new Drive($client);

            $fileMetadata = new DriveFile([
                'name' => $backupFile,
                'parents' => [$folderId] 
            ]);

            $content = file_get_contents($localPath);
            $file = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/x-sqlite3',
                'uploadType' => 'multipart'
            ]);

            if (!$file->id) {
                throw new \Exception(" File upload failed!");
            }

            $this->info("✅ Backup successful: $backupFile uploaded to Google Drive.");

            $this->grantAccessToFile($file->id);

        } catch (\Exception $e) {
            $this->error(" Backup failed! Error: " . $e->getMessage());
        }
    }

    private function grantAccessToFile($fileId)
    {
        $yourEmail = 'abanwbbekhiet@gmail.com';

        try {
            $client = $this->getGoogleClient();
            $driveService = new Drive($client);

            $permission = new Permission();
            $permission->setType('user');
            $permission->setRole('reader'); 
            $permission->setEmailAddress($yourEmail);

            $driveService->permissions->create($fileId, $permission);

            $this->info("✅ Access granted to $yourEmail for file ID: $fileId");

        } catch (\Exception $e) {
            $this->error("❌ Error granting access: " . $e->getMessage());
        }
    }

    private function getGoogleClient()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-drive.json')); 
        $client->addScope(Drive::DRIVE);
        $client->setAccessType('offline');

        return $client;
    }
}
