<?php

namespace App\ApiBundle\Service;

use App\ApiBundle\Payload\GetMessageRequest;
use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class FileFactory extends AbstractController
{
    const DEFAULT_PATH = '/var/storage/';

    public function __construct(
        private KernelInterface $kernel,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function saveMessageToFile(Message $message)
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->kernel->getProjectDir() . self::DEFAULT_PATH . $message->getUuid() . '.txt', base64_encode(serialize($message)));

        return $message;
    }

    public function getMessageFromFile(GetMessageRequest $messageRequest): ?Message
    {
        $filesystem = new Filesystem();
        $fileExists = $filesystem->exists($this->kernel->getProjectDir() . self::DEFAULT_PATH . $messageRequest->getUuid() . '.txt');

        if ($fileExists) {
            $fileContent = file_get_contents($this->kernel->getProjectDir() . self::DEFAULT_PATH . $messageRequest->getUuid() . '.txt');
            /** @var Message $message */
            $message = unserialize(base64_decode($fileContent));

            return $message;
        }

        return null;
    }
}