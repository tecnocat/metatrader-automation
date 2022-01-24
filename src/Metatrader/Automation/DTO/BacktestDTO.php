<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\DTO;

use App\Metatrader\Automation\Helper\BacktestHelper;

class BacktestDTO extends AbstractDTO
{
    public string    $expertAdvisorName;
    public \DateTime $from;
    public int       $initialDeposit;
    public string    $name;
    public string    $period;
    public string    $symbol;
    public \DateTime $to;
    protected bool   $ignoreMissing = true;

    public function __construct(array $parameters = [])
    {
        // TODO: Can we do this here? Helpers sucks
        $parameters['name']           = BacktestHelper::getBacktestName($parameters);
        $parameters['initialDeposit'] = (int) $parameters['initialDeposit'];
        $parameters['from']           = \DateTime::createFromFormat('Y-m-d', $parameters['from'])->modify('midnight');
        $parameters['to']             = \DateTime::createFromFormat('Y-m-d', $parameters['to'])->modify('midnight');

        parent::__construct($parameters);
    }
}
