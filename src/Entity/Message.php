<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="order_list")
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 */
class Message extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="uuid", type="uuid", nullable=false)
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(name="message", type="string", nullable=false)
     */
    private $message;
}