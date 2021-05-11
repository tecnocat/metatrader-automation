<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractBaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    public static function getNamespace(): string
    {
        return __NAMESPACE__;
    }
}
