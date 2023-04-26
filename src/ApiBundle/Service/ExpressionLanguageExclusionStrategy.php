<?php


namespace App\ApiBundle\Service;


use JMS\Serializer\Context;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use JMS\Serializer\Expression\Expression;
use JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\SerializationContext;

class ExpressionLanguageExclusionStrategy implements ExclusionStrategyInterface
{
    /**
     * @var ExpressionEvaluatorInterface
     */
    private $expressionEvaluator;

    public function __construct(ExpressionEvaluatorInterface $expressionEvaluator)
    {
        $this->expressionEvaluator = $expressionEvaluator;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkipClass(ClassMetadata $class, Context $navigatorContext): bool
    {
        if (null === $class->excludeIf) {
            return false;
        }

        $variables = [
            'context' => $navigatorContext,
            'class_metadata' => $class,
        ];
        if ($navigatorContext instanceof SerializationContext) {
            $variables['object'] = $navigatorContext->getObject();
        } else {
            $variables['object'] = null;
        }

        if (($class->excludeIf instanceof Expression) && ($this->expressionEvaluator instanceof CompilableExpressionEvaluatorInterface)) {
            $ret =  $this->expressionEvaluator->evaluateParsed($class->excludeIf, $variables);
            return $ret;
        }

        $ret =  $this->expressionEvaluator->evaluate($class->excludeIf, $variables);
        return $ret;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $navigatorContext): bool
    {
        if (null === $property->excludeIf) {
            return false;
        }

        $variables = [
            'context' => $navigatorContext,
            'property_metadata' => $property,
        ];
        if ($navigatorContext instanceof SerializationContext) {
            $variables['object'] = $navigatorContext->getObject();
        } else {
            $variables['object'] = null;
        }

        if (($property->excludeIf instanceof Expression) && ($this->expressionEvaluator instanceof CompilableExpressionEvaluatorInterface)) {
            $ret = $this->expressionEvaluator->evaluateParsed($property->excludeIf, $variables);
            return $ret;
        }

        $ret = $this->expressionEvaluator->evaluate($property->excludeIf, $variables);
        return $ret;
    }
}
