<?php

namespace App\ApiBundle\SerializerTypes;

use App\ApiBundle\Exceptions\ValidationException;
use App\ApiBundle\Traits\GroupsTrait;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ObjectDenormalizer extends BaseSerializer
{
    use GroupsTrait;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var bool
     */
    protected $validate = true;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        parent::__construct($serializer);
        $this->validator = $validator;
    }

    /**
     * @param string $class
     * @param array $data
     * @return mixed
     * @throws ValidationException
     */
    public function denormalize(string $class, array $data = [])
    {
        $context = $this->getDeserializationContext();
        $obj = $this->serializer->fromArray($data, $class, $context);

        if (!$this->isValidate()) {
            return $obj;
        }

        $errors = $this->validator->validate($obj, null, $this->groups ?? null);

        if (count($errors) > 0) {
            throw new ValidationException(
                $errors[0]->getMessage(),
                $errors[0]->getPropertyPath()
            );
        }

        return $obj;
    }

    /**
     * @return bool
     */
    public function isValidate(): bool
    {
        return $this->validate;
    }

    /**
     * @param bool $validate
     * @return ObjectDenormalizer
     */
    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function clearOptions(): void
    {
        $this->setValidate(true);
        $this->clearGroups();

    }
}
