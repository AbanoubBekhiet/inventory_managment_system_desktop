<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
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
        $localPath = database_path('database.sqlite'); // ✅ Ensure path is correct
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID'); // ✅ Your personal Drive folder ID

        try {
            // ✅ 1️⃣ Upload to Google Drive inside shared folder
            $client = $this->getGoogleClient();
            $driveService = new Drive($client);

            $fileMetadata = new DriveFile([
                'name' => $backupFile,
                'parents' => [$folderId] // ✅ Store inside personal folder
            ]);

            $content = file_get_contents($localPath);
            $file = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/x-sqlite3',
                'uploadType' => 'multipart'
            ]);

            if (!$file->id) {
                throw new \Exception("❌ File upload failed!");
            }

            $this->info("✅ Backup successful: $backupFile uploaded to Google Drive.");

            // ✅ 2️⃣ Grant Access to Your Email
            $this->grantAccessToFile($file->id);

        } catch (\Exception $e) {
            $this->error("❌ Backup failed! Error: " . $e->getMessage());
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
            $permission->setRole('reader'); // Change to 'writer' for edit access
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
        $client->setAuthConfig(storage_path('app/google-drive.json')); // ✅ Ensure this file exists
        $client->addScope(Drive::DRIVE);
        $client->setAccessType('offline');

        return $client;
    }
}
