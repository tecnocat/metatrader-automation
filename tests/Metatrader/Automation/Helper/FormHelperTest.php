<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\FormHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormHelperTest extends TestCase
{
    public function testAddField()
    {
        $formConfigBuilder = $this->createMock(FormConfigBuilderInterface::class);
        $formConfigBuilder->expects(static::once())->method('addModelTransformer')->with(
            new CallbackTransformer(
                function ($value)
                {
                    return !empty($value) ? serialize($value) : null;
                },
                function ($value)
                {
                    return !empty($value) ? unserialize($value) : null;
                }
            )
        )
        ;

        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder->expects(static::exactly(7))->method('getDataClass')->willReturn(FormHelperTestObject::class);
        $formBuilder->expects(static::once())->method('get')->with('items')->willReturn($formConfigBuilder);
        $formBuilder->expects(static::exactly(7))->method('add')->withConsecutive(
            [
                static::equalTo('active'),
                static::equalTo(CheckboxType::class),
                static::equalTo(
                    [
                        'false_values' => [
                            '0',
                            'false',
                        ],
                    ]
                ),
            ],
            [
                static::equalTo('amount'),
                static::equalTo(NumberType::class),
                static::equalTo([]),
            ],
            [
                static::equalTo('code'),
                static::equalTo(TextType::class),
                static::equalTo([]),
            ],
            [
                static::equalTo('id'),
                static::equalTo(IntegerType::class),
                static::equalTo([]),
            ],
            [
                static::equalTo('items'),
                static::equalTo(TextType::class),
                static::equalTo([]),
            ],
            [
                static::equalTo('name'),
                static::equalTo(TextType::class),
                static::equalTo([]),
            ],
            [
                static::equalTo('testedAt'),
                static::equalTo(DateType::class),
                static::equalTo(
                    [
                        'format' => 'yyyy-MM-dd',
                        'widget' => 'single_text',
                    ]
                ),
            ],
        )
        ;
        FormHelper::addField($formBuilder, 'active');
        FormHelper::addField($formBuilder, 'amount');
        FormHelper::addField($formBuilder, 'code');
        FormHelper::addField($formBuilder, 'id');
        FormHelper::addField($formBuilder, 'items');
        FormHelper::addField($formBuilder, 'name');
        FormHelper::addField($formBuilder, 'testedAt');
    }

    public function testGetFormEntityType()
    {
        $expected = 'App\Metatrader\Automation\Form\Type\FormHelperTestType';
        static::assertSame($expected, FormHelper::getFormEntityType(FormHelperTestObject::class));
    }

    public function testSetDefaults()
    {
        $expected        = [
            'allow_extra_fields' => true,
            'csrf_protection'    => false,
            'data_class'         => 'App\Metatrader\Automation\Entity\\' . __NAMESPACE__ . '\FormHelperTestObEntity',
        ];
        $optionsResolver = $this->createMock(OptionsResolver::class);
        $optionsResolver->expects(static::once())->method('setDefaults')->with(static::equalTo($expected));
        FormHelper::setDefaults($optionsResolver, FormHelperTestObject::class);
    }
}
