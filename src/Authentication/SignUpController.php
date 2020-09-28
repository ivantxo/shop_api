<?php


namespace App\Authentication;


use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


final class SignUpController
{
    /**
     * @var Storage $storage
     */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->validate();
        return $this->storage
            ->create($input->email(), $input->hashedPassword())
            ->then(
                function () {
                    return JsonResponse::created([]);
                }
            )
            ->otherwise(
                function (UserAlreadyExists $exception) {
                    return JsonResponse::badRequest(
                        ['email' => 'Email is already taken']
                    );
                }
            );
    }
}
