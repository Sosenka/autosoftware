<?php

namespace App\ApiBundle\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends \Exception
{
    private $field;

    public function __construct($message = "", string $field = "")
    {
        parent::__construct($message,Response::HTTP_BAD_REQUEST);
        $this->field = $field;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code'    => 'Validation Error',
            'field' => $this->field
        ];
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }
}
