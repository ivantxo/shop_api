<?php


namespace App\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use App\Core\JsonResponse;


final class SignInController
{
    /**
     * @var Authenticator $authenticator
     */
    private $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->validate();
        return $this->authenticator->authenticate($input->email(), $input->password())
            ->then(
                function ($jwt) {
                    return JsonResponse::ok(['token' => $jwt]);
                }
            )
            ->otherwise(
                function (BadCredentials $badCredentials) {
                    return JsonResponse::unauthorised();
                }
            )
            ->otherwise(
                function (UserNotFound $exception) {
                    return JsonResponse::unauthorised();
                }
            );
    }
}
