<?php

namespace reunionou\files\services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use Slim\Psr7\UploadedFile;

final class FileService
{
    public function createAvatar(UploadedFile $file, string $user_id): ?string
    {
        if ($file->getError() === UPLOAD_ERR_OK) {
            $target_dir = "public/images/avatars/";

            $filename = $user_id . '.' . pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

            $file->moveTo($target_dir . DIRECTORY_SEPARATOR . $filename);

            return $target_dir . DIRECTORY_SEPARATOR . $filename;
        }

        return null;
    }
}
