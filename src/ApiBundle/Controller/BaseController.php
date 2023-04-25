<?php

namespace App\ApiBundle\Controller;

use App\ApiBundle\Service\ExpressionLanguageExclusionStrategy;
use App\ApiBundle\Service\SerializationService;
use JMS\Serializer\Expression\ExpressionEvaluator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BaseController extends AbstractController
{
    protected $serializationService;

    private $eventDispatcher;

    public function setSerializationService(SerializationService $serializationService): void
    {
        $this->serializationService = $serializationService;
        $this->serializationService->setExclusionStrategies(
            [new ExpressionLanguageExclusionStrategy(new ExpressionEvaluator(new ExpressionLanguage()))]
        );
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @param Event $event
     * @param string $name
     */
    public function dispatchEvent(Event $event, string $name)
    {
        $this->eventDispatcher->dispatch($event, $name);
    }
}