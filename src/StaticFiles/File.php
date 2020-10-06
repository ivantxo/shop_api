<?php


namespace App\StaticFiles;


use React\Filesystem\Stream\ReadableStream;


final class File
{
    /**
     * @var ReadableStream $stream
     */
    public $stream;

    /**
     * @var string $mimeType
     */
    public $mimeType;

    public function __construct(ReadableStream $stream, string $mimeType)
    {
        $this->stream = $stream;
        $this->mimeType = $mimeType;
    }
}
