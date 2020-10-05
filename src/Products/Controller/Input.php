<?php


namespace App\Products\Controller;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
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
        $this->validateFields();
//        $this->validateUploadedFile();
    }

    public function price(): float
    {
        return (float)$this->request->getParsedBody()['price'];
    }

    public function name(): string
    {
        return $this->request->getParsedBody()['name'];
    }

    public function image(): ?UploadedFileInterface
    {
        $files = $this->request->getUploadedFiles();
        return $files['image'] ?? null;
    }

    private function validateFields(): void
    {
        $nameValidator = Validator::key(
            'name',
            Validator::allOf(
                Validator::notBlank(),
                validator::stringType()
            )
        )->setName('name');

        $priceValidator = Validator::key(
            'price',
            Validator::allOf(
                Validator::number(),
                Validator::positive(),
                Validator::notBlank()
            )
        )->setName('price');
        Validator::allOf($nameValidator, $priceValidator)->assert($this->request->getParsedBody());
    }

    private function validateUploadedFile(): void
    {
        if ($this->image() === null) {
            return;
        }
        $imageValidator = Validator::anyOf(
            Validator::mimetype('image/png'),
            Validator::mimetype('image/jpg')
        )->setName('image');
        $imageValidator->assert($this->image()->getClientFilename());
    }
}
