<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Form\Type;

use App\Metatrader\Automation\Helper\ClassHelper;
use App\Metatrader\Automation\Helper\FormHelper;
use App\Metatrader\Automation\Interfaces\NamespaceInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBaseType extends AbstractType implements NamespaceInterface
{
    public static function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    final public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        foreach (ClassHelper::getProperties($builder->getDataClass()) as $property)
        {
            FormHelper::addField($builder, $property->getName());
        }
    }

    final public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        FormHelper::setDefaults($resolver, ClassHelper::getClassName($this));
    }
}
