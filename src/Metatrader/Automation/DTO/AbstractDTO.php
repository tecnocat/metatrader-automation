<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\DTO;

use Spatie\DataTransferObject\DataTransferObject as DTO;

abstract class AbstractDTO extends DTO
{
    protected string $dateTimeFormat = 'Y-m-d';

    final public function toParameters(): array
    {
        $array = $this->toArray();

        foreach ($array as $index => $value)
        {
            switch (true)
            {
                case $value instanceof \DateTime:
                    $array[$index] = $value->format($this->dateTimeFormat);

                    break;

                default:
                    $array[$index] = (string) $value;
            }
        }

        return $array;
    }
}
