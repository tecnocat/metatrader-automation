<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

use App\Metatrader\Automation\Entity\BacktestEntity;

class FactoryBuildBacktestEntityEvent extends AbstractBuildEntityEvent
{
    private BacktestEntity $backtestEntity;

    public function getBacktestEntity(): BacktestEntity
    {
        return $this->backtestEntity;
    }

    public function setBacktestEntity(BacktestEntity $backtestEntity): void
    {
        $this->backtestEntity = $backtestEntity;
    }
}
