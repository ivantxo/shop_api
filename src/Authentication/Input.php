<?php


namespace App\Authentication;


use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;


final class Input
{
    /**
     * @var ServerRequestInterface $request
     */
    private $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function validate(): void
    {
        $emailValidator = Validator::key(
            'email',
            Validator::allOf(
                Validator::email(),
                Validator::notBlank(),
                Validator::stringType()
            )
        )->setName('email');

        $passwordValidator = Validator::key(
            'password',
            Validator::allOf(
                Validator::notBlank(),
                Validator::stringType()
            )
        )->setName('password');
        Validator::allOf(
            $emailValidator,
            $passwordValidator
        )->assert($this->request->getParsedBody());
    }

    public function email(): string
    {
        return $this->request->getParsedBody()['email'];
    }

    public function hashedPassword(): string
    {
        $rawPassword = $this->request->getParsedBody()['password'];
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }
}
