<?php

namespace App\ApiBundle\Controller;

use App\ApiBundle\Exceptions\ValidationException;
use App\ApiBundle\Payload\CreateMessageRequest;
use App\ApiBundle\Payload\GetMessageRequest;
use App\ApiBundle\Service\FileFactory;
use App\ApiBundle\Service\MessageFactory;
use App\ApiBundle\Voter\MessageVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends BaseController
{
    public function __construct(
        private MessageFactory $messageFactory,
        private FileFactory    $fileFactory
    )
    {
    }

    const ITEM_RESPONSE_GROUPS = [
        'message',
    ];

    public function createMessageAction(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(MessageVoter::POST_MESSAGE);
        $payload = $this->serializationService->denormalize(CreateMessageRequest::class, $request->request->all());
        $this->serializationService->setGroups(self::ITEM_RESPONSE_GROUPS);

        $factory = $this->messageFactory->createMessage($payload);
        $this->fileFactory->saveMessageToFile($factory);

        return new JsonResponse($this->serializationService->normalize(['uuid' => $factory->getUuid()]), Response::HTTP_OK);
    }

    public function getMessageAction(Request $request)
    {
        $payload = $this->serializationService->denormalize(GetMessageRequest::class, $request->request->all());
        $this->serializationService->setGroups(self::ITEM_RESPONSE_GROUPS);

        $message = $this->fileFactory->getMessageFromFile($payload);

        if ($message) {
            return new JsonResponse($this->serializationService->normalize(['message' => $message->getMessage()]), Response::HTTP_OK);

        }
        return new JsonResponse($this->serializationService->normalize(["message" => "Not Found"]), Response::HTTP_NOT_FOUND);
    }
}