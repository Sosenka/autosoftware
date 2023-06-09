<?php

namespace App\ApiBundle\Payload;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMessageRequest
{
    /**
     * @Serializer\Type("string")
     * @Assert\Type("string")
     * @Assert\NotBlank(message="proszę podać wiadomość")
     */
    protected $message;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }


}