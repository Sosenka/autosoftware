<?php

namespace App\ApiBundle\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;
use function getenv;
use function strtolower;

abstract class ApiJsonResponseBase extends JsonResponse
{
    public const PRODUCTION = 'prod';
    public const DEVELOPMENT = 'dev';

    /**
     * @var string
     */
    protected $responseKey = 'success';


    /**
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     */
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        $data = $this->prepareProductionResponse($data);
        if ($json) {
            $data = json_encode($data);
        }
        parent::__construct($data, $status, $headers, $json);
    }

    /**
     * @param null $data
     * @return |null
     */
    private function prepareProductionResponse($data = null)
    {
        if ($this->shouldReturnData()) {
            return $data;
        }

        $fieldsToRemove = ['message', 'trace'];
        foreach ($fieldsToRemove as $field) {
            if (isset($data[$field])) {
                unset($data[$field]);
            }

            if (isset($data[$this->responseKey][$field])) {
                unset($data[$this->responseKey][$field]);
            }
        }

        return $data;
    }

    /**
     * @return string
     */
    private function getEnvironment(): ?string
    {
        $env = self::DEVELOPMENT;

        if (isset($_ENV['APP_ENV']) && false !== $_ENV['APP_ENV']) {
            $env = $_ENV['APP_ENV'];
        }
        $isEnv = getenv('APP_ENV');
        if (false !== $isEnv) {
            $env = $isEnv;
        }


        return strtolower($env);
    }

    private function shouldReturnData() {
        $env = $this->getEnvironment();
        if (self::PRODUCTION !== $env) {
            return true;
        }

        return getenv("RETURN_MESSAGES") == '1';
    }
}
