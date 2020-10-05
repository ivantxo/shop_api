<?php


namespace App\Core;


use Psr\Http\Message\UploadedFileInterface;
use React\Filesystem\FilesystemInterface;
use React\Promise\PromiseInterface;


final class Uploader
{
    private CONST UPLOADS_DIR = 'uploads';

    /**
     * @var FilesystemInterface $filesystem
     */
    private $filesystem;

    /**
     * @var string $projectRoot
     */
    private $projectRoot;

    public function __construct(FilesystemInterface $filesystem, string $projectRoot)
    {
        $this->filesystem = $filesystem;
        $this->projectRoot = $projectRoot;
    }

    public function upload(UploadedFileInterface $file): PromiseInterface
    {
        $uploadPath = $this->makeFilePath($file);
        $fullPath = $this->projectRoot . '/' . $uploadPath;

        return $this->filesystem->file($fullPath)
            ->putContents((string)$file->getStream())
            ->then(
                function () use ($uploadPath) {
                    return $uploadPath;
                }
            );
    }

    private function makeFilePath(UploadedFileInterface $file): string
    {
        preg_match('/^.*\.(.+)$/', $file->getClientFilename(), $fileNameParsed);
        return implode('', [
            self::UPLOADS_DIR,
            '/',
            md5((string)$file->getStream()),
            '.',
            $fileNameParsed[1]
        ]);
    }
}
