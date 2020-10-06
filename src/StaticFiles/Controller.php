<?php


namespace App\StaticFiles;


use App\Core\JsonResponse;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;


final class Controller
{
    /**
     * @var Webroot $webroot
     */
    private $webroot;

    public function __construct(Webroot $webroot)
    {
        $this->webroot = $webroot;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->webroot->file($request->getUri()->getPath())
            ->then(
                function (File $file) {
                    return new Response(
                        200,
                        ['Content-Type' => $file->mimeType],
                        $file->stream
                    );
                }
            )
            ->otherwise(
                function (FileNotFound $exception) {
                    return JsonResponse::notFound();
                }
            )
            ->otherwise(
                function (Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}
