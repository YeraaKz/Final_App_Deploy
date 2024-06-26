<?php

namespace App\Service;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3Service
{
    private $s3Client;
    private $bucketName;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => getenv('AWS_REGION'),
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->bucketName = getenv('AWS_BUCKET_NAME');
    }

    public function uploadFile(UploadedFile $file): string
    {
        $filePath = 'uploads/' . uniqid() . '.' . $file->guessExtension();

        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key'    => $filePath,
            'SourceFile' => $file->getPathname(),
        ]);

        return $result['ObjectURL'];
    }
}