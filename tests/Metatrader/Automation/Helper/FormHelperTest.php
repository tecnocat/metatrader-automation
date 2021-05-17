<?php

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\FormHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormHelperTest extends TestCase
{
    public function testSetDefaults()
    {
        $expected        = [
            'allow_extra_fields' => true,
            'csrf_protection'    => false,
            'data_class'         => 'App\Metatrader\Automation\Entity\\' . __NAMESPACE__ . '\FormHelperTestObEntity',
        ];
        $optionsResolver = $this->createMock(OptionsResolver::class);
        $optionsResolver->expects($this->once())->method('setDefaults')->with($this->equalTo($expected));
        FormHelper::setDefaults($optionsResolver, FormHelperTestObject::class);
    }

    public function testAddField()
    {
        $formBuilder = $this->createMock(FormBuilder::class);
        $formBuilder->expects($this->exactly(3))->method('getDataClass')->willReturn(ClassHelperTestObject::class);
        $formBuilder->expects($this->exactly(3))->method('add')->withConsecutive(
            [$this->equalTo('code'), $this->equalTo(TextType::class), $this->equalTo([])],
            [$this->equalTo('id'), $this->equalTo(IntegerType::class), $this->equalTo([])],
            [$this->equalTo('name'), $this->equalTo(TextType::class), $this->equalTo([])],
        );
        FormHelper::addField($formBuilder, 'code');
        FormHelper::addField($formBuilder, 'id');
        FormHelper::addField($formBuilder, 'name');
    }

    public function testGetFormEntityType()
    {
    }
}
