<?php

namespace App\ApiBundle\SerializerTypes;

use App\ApiBundle\Traits\ExclusionStrategiesTrait;
use App\ApiBundle\Traits\GroupsTrait;

class ObjectSerializer extends BaseSerializer
{
    use GroupsTrait;

    use ExclusionStrategiesTrait;

    /**
     * @param $data
     * @param string $serializationType
     * @return string
     */
    public function serialize($data, string $serializationType = SerializationType::JSON): string
    {
        $context = $this->getSerializationContext();
        if (count($this->groups)) {
            $context->setGroups($this->groups);
        }
        foreach ($this->exclusionStrategies as $exclusionStrategy) {
            $context->addExclusionStrategy($exclusionStrategy);
        }

        return $this->serializer->serialize($data, $serializationType, $context);
    }

    public function clearOptions()
    {
        $this->clearGroups();
        $this->clearExclusionStrategies();
    }
}
