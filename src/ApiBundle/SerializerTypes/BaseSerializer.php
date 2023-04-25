<?php

namespace App\ApiBundle\SerializerTypes;

use JMS\Serializer\ContextFactory\DefaultDeserializationContextFactory;
use JMS\Serializer\ContextFactory\DefaultSerializationContextFactory;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \DateTime;

abstract class BaseSerializer
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * BaseSerializer constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->buildForIdenticalNaming();
    }

    private function buildForIdenticalNaming()
    {
        $serializebuilder = SerializerBuilder::create();
        $serializebuilder->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy());
        $serializebuilder->addDefaultHandlers();
        $this->configureHandlers($serializebuilder);

        $this->serializer = $serializebuilder->build();
    }

    /**
     * @param SerializerBuilder $serializebuilder
     */
    protected function configureHandlers(SerializerBuilder $serializebuilder): void
    {
        $serializebuilder->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerHandler(
                GraphNavigator::DIRECTION_DESERIALIZATION,
                'trimmed',
                "json",
                function ($visitor, $obj, array $type) {
                    if (is_string($obj)) {
                        return trim($obj);
                    }

                    return null;
                }
            );
            $registry->registerHandler(
                GraphNavigator::DIRECTION_SERIALIZATION,
                "DateTime",
                "json",
                function ($visitor, DateTime $obj, array $type) {
                    return $obj->format(self::DATE_FORMAT);
                }
            );
            $registry->registerHandler(
                GraphNavigator::DIRECTION_DESERIALIZATION,
                "DateTime",
                "json",
                function ($visitor, string $obj, array $type) {
                    $date = DateTime::createFromFormat("Y-m-d H:i:s", $obj);
                    if (!$date) {
                        $date = DateTime::createFromFormat(self::DATE_FORMAT, $obj);
                    }

                    return $date;
                }
            );
            $registry->registerHandler(
                GraphNavigator::DIRECTION_DESERIALIZATION,
                "UploadedFile",
                "json",
                function ($visitor, $obj, array $type) {
                    return $obj instanceof UploadedFile ? $obj : null;
                }
            );
        });
    }

    /**
     * @return SerializationContext
     */
    protected function getSerializationContext(): SerializationContext
    {
        return (new DefaultSerializationContextFactory())->createSerializationContext();
    }

    /**
     * @return DeserializationContext
     */
    protected function getDeserializationContext(): DeserializationContext
    {
        return (new DefaultDeserializationContextFactory())->createDeserializationContext();
    }
}
