<?php

namespace App\ApiBundle\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use \Throwable;

class ApiJsonHandleException extends \Exception
{
    protected $codeMessage;

    /**
     * ApiJsonHandleException constructor.
     * @param string $message
     * @param string $codeMessage
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = '',
        string $codeMessage = '',
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null
    ) {
        $this->codeMessage = $codeMessage;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getCodeMessage(): string
    {
        return $this->codeMessage;
    }
}
