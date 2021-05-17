<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class BacktestHelper
{
    public static function addBacktestName(array $data): array
    {
        $parameters   = [
            $data['expertAdvisor'],
            $data['symbol'],
            $data['period'],
            $data['deposit'],
            $data['from'],
            $data['to'],
        ];
        $data['name'] = implode(':', $parameters);

        return $data;
    }
}
