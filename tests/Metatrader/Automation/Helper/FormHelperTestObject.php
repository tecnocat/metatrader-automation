<?php

namespace App\Tests\Metatrader\Automation\Helper;

use Symfony\Component\Form\AbstractType;

class FormHelperTestObject extends AbstractType
{
    private string $code;

    private int $id;

    private string $name;
}