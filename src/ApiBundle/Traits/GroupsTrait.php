<?php

namespace App\ApiBundle\Traits;

use App\ApiBundle\SerializerTypes\ObjectDenormalizer;
use App\ApiBundle\SerializerTypes\ObjectNormalizer;
use App\ApiBundle\SerializerTypes\ObjectSerializer;
use App\ApiBundle\Service\SerializationService;

trait GroupsTrait
{
    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     * @return ObjectSerializer|ObjectDenormalizer|ObjectNormalizer|SerializationService|GroupsTrait
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function addGroup($group): self
    {
        if (!in_array($group, $this->groups, true)) {

            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * Clear groups
     */
    public function clearGroups(): void
    {
        $this->groups = [];
    }

    /**
     * @param string $group
     */
    public function removeGroup(string $group): void
    {
        if (in_array($group, $this->groups)) {
            unset($this->groups[array_search($group, $this->groups)]);
        }
    }
}
