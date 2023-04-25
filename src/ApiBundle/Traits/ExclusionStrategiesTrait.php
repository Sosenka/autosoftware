<?php

namespace App\ApiBundle\Traits;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;

trait ExclusionStrategiesTrait
{
    protected $exclusionStrategies = [];

    public function setExclusionStrategies(array $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->addExclusionStrategy($strategy);
        }
    }

    public function addExclusionStrategy(ExclusionStrategyInterface $strategy)
    {
        $this->exclusionStrategies[] = $strategy;
    }

    public function clearExclusionStrategies()
    {
        $this->exclusionStrategies = [];
    }

    public function removeExclusionStrategy(ExclusionStrategyInterface $strategy)
    {
        if (in_array($strategy, $this->exclusionStrategies)) {
            unset($this->exclusionStrategies[array_search($strategy, $this->exclusionStrategies)]);
        }
    }
}
