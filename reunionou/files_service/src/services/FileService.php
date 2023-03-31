<?php

namespace reunionou\files\services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use Slim\Psr7\UploadedFile;

final class FileService
{
    public function createAvatar(UploadedFile $file, string $user_id): ?string
    {
        try {
            if ($file->getError() === UPLOAD_ERR_OK) {
                $target_dir = "/var/www/assets/images/avatars";

                $filename = $user_id . '.' . pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

                $file->moveTo($target_dir . DIRECTORY_SEPARATOR . $filename);

                return $target_dir . DIRECTORY_SEPARATOR . $filename;
            }
        } catch (\Throwable $th) {
            return null;
        }


        return null;
    }

    public function getAvatar(string $user_id): ?string
    {
        try {
            $target_dir = "/var/www/assets/images/avatars";

            if (!is_dir($target_dir)) {
                echo 'Target directory does not exist';
            }

            if (!is_writable($target_dir)) {
                echo 'Target directory is not writable';
            }

            $filename = $user_id . '.jpg';

            if (file_exists($target_dir . DIRECTORY_SEPARATOR . $filename)) {
                return $target_dir . DIRECTORY_SEPARATOR . $filename;
            }


            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
