<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Domain\BacktestInterface;
use App\Metatrader\Automation\Model\AbstractModel;

class FactoryBuildBacktestEvent extends AbstractEvent
{
    private BacktestInterface $backtest;
    private AbstractModel     $model;

    public function __construct(AbstractModel $model)
    {
        $this->model = $model;
    }

    public function getBacktest(): BacktestInterface
    {
        return $this->backtest;
    }

    public function setBacktest(BacktestInterface $backtest): void
    {
        $this->backtest = $backtest;
    }

    public function getModel(): AbstractModel
    {
        return $this->model;
    }
}
