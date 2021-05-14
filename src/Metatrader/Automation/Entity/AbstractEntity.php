<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Entity;

use App\Metatrader\Automation\Interfaces\EntityInterface;
use App\Metatrader\Automation\Interfaces\NamespaceInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity implements EntityInterface, NamespaceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public static function getNamespace(): string
    {
        return __NAMESPACE__;
    }
}
