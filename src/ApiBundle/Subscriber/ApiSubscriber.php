<?php

namespace App\ApiBundle\Subscriber;

use App\ApiBundle\Exceptions\ApiJsonResponseError;
use App\ApiBundle\Exceptions\ValidationException;
use App\ApiBundle\Exceptions\ApiException;
use JsonException;
use App\ApiBundle\Exceptions\ApiJsonHandleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;


class ApiSubscriber implements EventSubscriberInterface
{
    private const JSON_TO_ARRAY_CONVERT = 'jsonToArrayConvert';
    private const API_EXCEPTION = 'apiException';
    public const UNAUTHORIZED = 'Unauthorized';
    public const FORBIDDEN = 'Forbidden';
    public const NOT_FOUND = 'Not Found';
    public const INTERNAL_ERROR = 'Internal Error';

    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ControllerEvent $event
     * @throws \JsonException
     */
    public function jsonToArrayConvert(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        $request = $event->getRequest();
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $request->request->replace(is_array($data) ? $data : []);
        }
    }

    /**
     * @param ExceptionEvent $event
     */
    public function apiException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof JsonException) {
            $response = new ApiJsonResponseError(
                'JSON '.$exception->getMessage()
            );
            $event->setResponse($response);
            $this->logger->warning("Error response", ['message' => 'JSON '.$exception->getMessage()]);
        }
        if ($exception instanceof HttpException) {
            if ($this->isAdminSite($event->getRequest())) { //error twig template here for admin
                return;
            }
            $response = new ApiJsonResponseError(
                $exception->getMessage(),
                $this->getMessages($exception->getStatusCode()),
                $exception->getStatusCode()
            );

            $this->logger->warning(
                "Error response",
                ['message' => $exception->getMessage(), 'messageCode' => $this->getMessages($exception->getStatusCode())]
            );
            $event->setResponse($response);
        }

        if ($exception instanceof ApiException) {

            $response = new ApiJsonResponseError(
                $exception->getMessage(),
                $exception->getCodeMessage(),
                Response::HTTP_BAD_REQUEST, [],
                $exception->getData(),
            );
            $this->logger->warning(
                "Error response",
                ['message' => $exception->getMessage(), 'messageCode' => $exception->getCodeMessage()]
            );


            $event->setResponse($response);
        } elseif ($exception instanceof ApiJsonHandleException) {
            $response = new ApiJsonResponseError(
                $exception->getMessage(),
                $exception->getCodeMessage()
            );
            $this->logger->warning(
                "Error response",
                ['message' => $exception->getMessage(), 'messageCode' => $exception->getCodeMessage()]
            );
            $event->setResponse($response);
        } elseif ($exception instanceof ValidationException) {
            $response = new ApiJsonResponseError(
                "Validation error",
                "Api_Error_Validation_Exception",
                Response::HTTP_BAD_REQUEST,
                [],
                ['field' => $exception->getField(), 'error' => $exception->getMessage()]
            );
            $this->logger->warning(
                "Error response",
                [
                    'message'     => "Validation error",
                    'messageCode' => "500",
                    'field'       => $exception->getField(),
                    'error'       => $exception->getMessage(),
                ]
            );
            $event->setResponse($response);
        }
    }

    /**
     * @param string|null $message
     * @return array|string|null
     */
    private function getMessages(string $message = null)
    {
        $messages = [
            Response::HTTP_UNAUTHORIZED          => self::UNAUTHORIZED,
            Response::HTTP_FORBIDDEN             => self::FORBIDDEN,
            Response::HTTP_NOT_FOUND             => self::NOT_FOUND,
            Response::HTTP_INTERNAL_SERVER_ERROR => self::INTERNAL_ERROR,
        ];

        if ($message) {
            return $messages[$message] ?? null;
        }

        return $messages;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION  => self::API_EXCEPTION,
            KernelEvents::CONTROLLER => self::JSON_TO_ARRAY_CONVERT,
        );
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isApiSite(Request $request): bool
    {
        return false !== stripos($request->getPathInfo(), '/api');
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isAdminSite(Request $request): bool
    {
        return false !== stripos($request->getPathInfo(), '/admin');
    }
}
