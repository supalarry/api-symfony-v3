<?php


namespace App;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\JsonToArrayException;
use Symfony\Component\HttpFoundation\RequestStack;

class JsonToArray
{
    private $request;
    private $errors;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->errors = [];
    }

    public function retrieve(): array
    {
        $dataJson = $this->request->getContent();
        $dataDecoded = json_decode($dataJson, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return ($dataDecoded);
        }
        $this->errors['json'] = json_last_error_msg();
        throw new JsonToArrayException($this->errors);
    }
}
