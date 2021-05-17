<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

class ClassHelperTestObjectSource extends ClassHelperTestObject
{
    private bool      $commonBool       = false;
    private \DateTime $commonDateTime;
    private float     $commonFloat      = 123.456;
    private string    $commonString     = 'common';
    private           $nonTypedProperty = 'non typed';
    private ?int      $nullValue        = null;
    private string    $onlyInSource;

    public function __construct(\DateTime $dateTime)
    {
        $this->commonDateTime = $dateTime;
    }

    public function getCommonDateTime(): \DateTime
    {
        return $this->commonDateTime;
    }
}
