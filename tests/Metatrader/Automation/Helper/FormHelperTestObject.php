<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use Symfony\Component\Form\AbstractType;

class FormHelperTestObject extends AbstractType
{
    private bool      $active;
    private float     $amount;
    private string    $code;
    private int       $id;
    private array     $items;
    private string    $name;
    private \DateTime $testedAt;
}
