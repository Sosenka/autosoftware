<?php

namespace App\ApiBundle\Service;

use App\ApiBundle\Payload\CreateMessageRequest;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Uid\Factory\UuidFactory;

class MessageFactory extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UuidFactory $uuidFactory,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function createMessage(CreateMessageRequest $payload)
    {
        $message = new Message();
        $message->setMessage($payload->getMessage());
        $message->setUuid($this->uuidFactory->create());
        $message->update();

        return $message;
    }
}