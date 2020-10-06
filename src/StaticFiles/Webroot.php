<?php


namespace App\StaticFiles;


use Narrowspark\MimeType\MimeTypeExtensionGuesser;
use React\Filesystem\FilesystemInterface;
use React\Filesystem\Node\FileInterface;
use React\Filesystem\Stream\ReadableStream;
use React\Promise\PromiseInterface;


final class Webroot
{
    private $filesystem;
    private $projectRoot;

    public function __construct(FilesystemInterface $filesystem, string $projectRoot)
    {
        $this->filesystem = $filesystem;
        $this->projectRoot = $projectRoot;
    }

    public function file(string $path): PromiseInterface
    {
        $file = $this->filesystem->file($this->projectRoot . $path);
        return $file->exists()
            ->then(
                function () use ($file) {
                    return $this->readFile($file);
                },
                function () {
                    throw new FileNotFound();
                }
            );
    }

    private function readFile(FileInterface $file): PromiseInterface
    {
        return $file->open('r')
            ->then(
                function (ReadableStream $stream) use ($file) {
//                    $mimeType = MimeTypeExtensionGuesser::guess($file->getPath());
                    $mimeType = 'image/png';
                    return new File($stream, $mimeType);
                }
            );
    }
}
