<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class BaseEntity
 * @package App\Entity
 * @MappedSuperclass
 * @HasLifecycleCallbacks()
 */
class BaseEntity
{
    /**
     * @var \DateTime
     * @Column(type="datetime", nullable=true)
     * @Serializer\Groups({"created", "base"})
     * @Serializer\Type(name="DateTime")
     */
    protected $deleted;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     * @Serializer\Groups({"created", "base"})
     * @Serializer\Type(name="DateTime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     * @Serializer\Groups({"created", "base"})
     * @Serializer\Type(name="DateTime")
     */
    protected $modified;

    /**
     * @return \DateTime|null
     */
    final function getDeleted(): ?\DateTime
    {
        return $this->deleted;
    }

    /**
     * @param \DateTime|null $deleted
     */
    final function setDeleted(?\DateTime $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return \DateTime|null
     */
    final function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    final function setCreated(?\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    final function getModified(): ?\DateTime
    {
        return $this->modified;
    }

    /**
     * @param \DateTime|null $modified
     */
    final function setModified(?\DateTime $modified): void
    {
        $this->modified = $modified;
    }

    /**
     * @throws \Exception
     * @PreUpdate
     * @PrePersist
     */
    final function update()
    {
        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime());
        }
        $this->setModified(new \DateTime());
    }
}