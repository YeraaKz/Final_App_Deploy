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
            'region' => $_ENV['AWS_REGION'],
            'credentials' => [
                'key'    => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            ],
        ]);

        $this->bucketName = $_ENV['AWS_BUCKET_NAME'];
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