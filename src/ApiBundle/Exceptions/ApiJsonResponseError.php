<?php

namespace App\ApiBundle\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ApiJsonResponseError extends ApiJsonResponseBase
{
    protected $responseKey = 'error';

    /**
     * @param string|null $message
     * @param string|null $messageCode
     * @param int $status
     * @param array $trace
     * @param null $data
     * @param array $headers
     * @param bool $json
     */
    public function __construct(
        string $message = null,
        string $messageCode = null,
        int $status = Response::HTTP_BAD_REQUEST,
        array $trace = [],
        $data = null,
        array $headers = [],
        bool $json = false
    ) {
        if ($json) {
            $data = json_decode($data, true);
        }

        $data = $this->prepareApiResponse($data, $message, $messageCode, $trace);

        parent::__construct($data, $status, $headers, $json);
    }

    /**
     * @param null $data
     * @param string|null $message
     * @param string|null $messageCode
     * @param int $status
     * @return array
     */
    protected function prepareApiResponse(
        $data = null,
        string $message = null,
        string $messageCode = null,
        array $trace = []
    ): array {

        if ($data) {
            $responseData[$this->responseKey]['data'] = $data;
        }
        if ($message) {
            $responseData[$this->responseKey]['message'] = $message;
        }
        if ($messageCode) {
            $responseData[$this->responseKey]['messageCode'] = $messageCode;
        }
        if ($trace) {
            $responseData[$this->responseKey]['trace'] = $trace;
        }

        return $responseData;
    }
}
