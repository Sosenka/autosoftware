<?php

namespace App\ApiBundle\Controller;

use App\ApiBundle\Payload\CreateMessageRequest;
use App\ApiBundle\Service\MessageFactory;
use App\ApiBundle\Voter\MessageVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends BaseController
{
    public function __construct(private MessageFactory $messageFactory)
    {}

    const ITEM_RESPONSE_GROUPS = [
        'message',
    ];

    public function createMessageAction(Request $request): JsonResponse
    {
        return new JsonResponse($this->serializationService->normalize("test"), Response::HTTP_OK);
        $this->denyAccessUnlessGranted(MessageVoter::POST_MESSAGE);
        $payload = $this->serializationService->denormalize(CreateMessageRequest::class, $request->request->all());
        $this->serializationService->setGroups(self::ITEM_RESPONSE_GROUPS);

//        $factory =

        return new JsonResponse($this->serializationService->normalize($message), Response::HTTP_OK);
    }
}