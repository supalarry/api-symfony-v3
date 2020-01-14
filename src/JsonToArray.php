<?php


namespace App;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\JsonToArrayException;

class JsonToArray
{
    private $request;
    private $errors;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->errors = [];
    }

    public function retrieve(): array
    {
        $dataJson = $this->request->getContent();
        $dataDecoded = json_decode($dataJson, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return ($dataDecoded);
        }
        $this->errors['json'] = 'Invalid JSON body';
        throw new JsonToArrayException($this->errors);
    }
}
