<?php


namespace App\Authentication;


final class User
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $email
     */
    public $email;

    /**
     * @var string $password
     */
    public $password;

    public function __construct(int $id, string $email, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }
}
