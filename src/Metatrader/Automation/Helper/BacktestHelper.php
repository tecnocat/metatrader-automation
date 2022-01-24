<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use Symfony\Component\Form\FormEvent;

class BacktestHelper
{
    public static function addBacktestName(FormEvent $event): void
    {
        $data         = $event->getData();
        $data['name'] = self::getBacktestName($data);
        $event->setData($data);
    }

    public static function getBacktestName(array $data): string
    {
        $parameters = [
            $data['expertAdvisorName'],
            $data['symbol'],
            $data['period'],
            $data['from'],
            $data['to'],
            $data['initialDeposit'],
        ];

        return implode(':', $parameters);
    }
}
