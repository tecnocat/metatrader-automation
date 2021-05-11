<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Form\Type;

use App\Metatrader\Automation\Entity\AbstractBaseEntity;
use App\Metatrader\Automation\Helper\ClassTools;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBaseType extends AbstractType
{
    final public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'allow_extra_fields' => true,
                'csrf_protection'    => false,
                'data_class'         => $this->getEntityClass(),
            ]
        );
    }

    private function getEntityClass(): string
    {
        $entityName = mb_substr(ClassTools::getShortName($this), 0, -4) . 'Entity';

        return AbstractBaseEntity::getNamespace() . '\\' . $entityName;
    }
}
