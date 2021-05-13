<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Entity\AbstractEntity;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormHelper
{
    public static function addField(FormBuilderInterface $builder, string $name): void
    {
        $type = ClassHelper::getPropertyType($builder->getDataClass(), $name);
        $builder->add($name, self::getFormType($type), self::getOptionsType($type));
    }

    public static function setDefaults(OptionsResolver $resolver, string $className): void
    {
        $resolver->setDefaults(
            [
                'allow_extra_fields' => true,
                'csrf_protection'    => false,
                'data_class'         => self::getEntityClass($className),
            ]
        );
    }

    private static function getEntityClass(string $className): string
    {
        return AbstractEntity::getNamespace() . '\\' . mb_substr($className, 0, -4) . 'Entity';
    }

    private static function getFormType(string $type): string
    {
        switch (mb_strtolower($type))
        {
            case 'bool':
                return CheckboxType::class;

            case 'datetime':
                return DateType::class;

            case 'float':
                return NumberType::class;

            case 'int':
                return IntegerType::class;

            default:
                return TextType::class;
        }
    }

    private static function getOptionsType(string $type): array
    {
        switch (mb_strtolower($type))
        {
            case 'bool':
                return [
                    'false_values' => [
                        '0',
                        'false',
                    ],
                ];

            case 'datetime':
                return [
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
                ];

            default:
                return [];
        }
    }
}
