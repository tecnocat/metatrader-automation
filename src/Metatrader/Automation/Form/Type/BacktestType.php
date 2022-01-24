<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Form\Type;

use App\Metatrader\Automation\Helper\BacktestHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BacktestType extends AbstractBaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void
            {
                BacktestHelper::addBacktestName($event);
            }
        );
    }
}
