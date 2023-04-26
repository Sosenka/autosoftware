<?php

namespace App\ApiBundle\Exceptions;

use App\ApiBundle\Exceptions\ApiJsonHandleException;

class ApiException extends ApiJsonHandleException
{
    private $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return ApiException
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
