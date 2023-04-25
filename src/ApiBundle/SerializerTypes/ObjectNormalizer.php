<?php

namespace App\ApiBundle\SerializerTypes;

use App\ApiBundle\Traits\ExclusionStrategiesTrait;
use App\ApiBundle\Traits\GroupsTrait;

class ObjectNormalizer extends BaseSerializer
{
    use GroupsTrait;

    use ExclusionStrategiesTrait;

    /**
     * @param $paginator
     * @param null $mapFunction
     * @return array
     */
    public function normalizePaginatorRecords($paginator, $mapFunction = null): array
    {
        $records = [];
        foreach ($paginator as $record) {
            if($mapFunction){
                $records[] = $this->normalize($mapFunction($record));
            }else {
                $records[] = $this->normalize($record);
            }
        }

        return $records;
    }

    /**
     * @param $object
     * @return array
     */
    public function normalize($object): array
    {
        $context = $this->getSerializationContext()->setSerializeNull(true);
        if (count($this->groups)) {
            $context->setGroups($this->groups);
        }


        foreach ($this->exclusionStrategies as $exclusionStrategy) {
            $context->addExclusionStrategy($exclusionStrategy);
        }


        return $this->serializer->toArray($object, $context);
    }

    public function clearOptions()
    {
        $this->clearGroups();
        $this->clearExclusionStrategies();
    }
}
