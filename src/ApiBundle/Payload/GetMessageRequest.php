<?php

namespace App\ApiBundle\Payload;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class GetMessageRequest
{
    /**
     * @Serializer\Type("string")
     * @Assert\Type("string")
     * @Assert\NotBlank(message="Proszę podać uuid")
     */
    protected $uuid;

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
    }




}