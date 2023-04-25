<?php

namespace App\ApiBundle\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;
use App\ApiBundle\Exceptions\ValidationException;
use App\ApiBundle\SerializerTypes\ObjectDenormalizer;
use App\ApiBundle\SerializerTypes\ObjectNormalizer;
use App\ApiBundle\SerializerTypes\ObjectSerializer;
use App\ApiBundle\SerializerTypes\SerializationType;
use App\ApiBundle\Traits\ExclusionStrategiesTrait;
use App\ApiBundle\Traits\GroupsTrait;

class SerializationService
{
    use GroupsTrait;
    use ExclusionStrategiesTrait;

    /**
     * @var ObjectNormalizer
     */
    protected $normalizer;

    /**
     * @var ObjectDenormalizer
     */
    protected $denormalizer;

    /**
     * @var ObjectSerializer
     */
    protected $serializer;

    /**
     * SerializationService constructor.
     * @param ObjectNormalizer $normalizer
     * @param ObjectDenormalizer $denormalizer
     * @param ObjectSerializer $serializer
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        ObjectDenormalizer $denormalizer,
        ObjectSerializer $serializer
    ) {
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
        $this->serializer = $serializer;
    }

    /**
     * @param $object
     * @return array
     */
    public function normalize($object): array
    {
        $this->normalizer->setGroups($this->groups);
        $this->normalizer->setExclusionStrategies($this->exclusionStrategies);
        $response = $this->normalizer->normalize($object);
        $this->clearConfiguration();
        return $response;
    }

    /**
     * Clear Groups
     */
    public function clearConfiguration(): void
    {
        $this->clearGroups();
        $this->clearExclusionStrategies();
        $this->normalizer->clearOptions();
        $this->denormalizer->clearOptions();
        $this->serializer->clearOptions();
    }

    /**
     * @param string $class
     * @param array $data
     * @param bool $validate
     * @return mixed
     * @throws ValidationException
     */
    public function denormalize(string $class, array $data = [], bool $validate = true): object
    {
        $this->denormalizer->setGroups($this->groups);
        $this->denormalizer->setValidate($validate);

        $response = $this->denormalizer->denormalize($class, $data);

        $this->clearConfiguration();

        return $response;
    }

    /**
     * @param $data
     * @param string $serializationType
     * @return string
     */
    public function serialize($data, string $serializationType = SerializationType::JSON): string
    {
        $this->serializer->setGroups($this->groups);
        $this->serializer->setExclusionStrategies($this->exclusionStrategies);

        $response = $this->serializer->serialize($data, $serializationType);

        $this->clearConfiguration();

        return $response;
    }
}
