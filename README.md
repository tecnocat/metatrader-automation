# Metatrader Automation

This project born due to the need to run many instances of Metatrader 4 to do an automatic backtesting with Tick Data
Suite enabled, and my first php script was totally destroying the PHP OOP concepts and best practices becoming a big
monster, and aditionally is taken soo long time to do the backtesting because only use one instance of Metatrader 4 (only
one test at a time)

## Current development

* ~~Prepare command basics with components and workflow events~~
* ~~System to load Expert Advisor settings based on .yaml files~~
* ~~Backtest report parser (to future store in database with Doctrine)~~
* ~~Expert Advisor iterator for each available backtest settings~~
* ~~Form builder and helper to handle dynamic forms based on entities~~
* Config.ini and ExpertAdvisor.ini files to auto-start up Metatrader 4
* Command to import already executed Metatrader backtest reports

## Next steps

* Cluster generator (copy many instances of main Metatrader 4)
* Workflow steps to detect Metatrader 4 instances free to run
* System to handle start / stop of Tick Data Suite during backtest
* Multiple Expert Advisors and symbols to backtesting at same time

### Requirements

* PHP 7.4.6
* Some PHP extensions enabled on PHP CLI (run composer install to know what)
* Metatrader 4
* An Expert Advisor implemented in `src/Metatrader/Automation/ExpertAdvisor`

### Metatrader 4 Implementation

See `config/services.yaml` to set up all the settings for your Expert Advisors and Metatrader 4 data directory

### PHP Expert Advisor implementation

````php
<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\EntityInterface;

class YourExpertAdvisorName extends AbstractExpertAdvisor
{
    public function getBacktestGenerator(EntityInterface $backtestEntity): \Generator
    {
        // Your iteration logic to generate a unique backtest report name (see Prudencio)
        for ($i = 1; $i <= 10; $i++)
        {
            yield "some-unique-name-for-report-based-on-some-parameters-$i.html";
        }
    }
}
````

### How to install

````bash
git clone https://github.com/tecnocat/metatrader-automation.git
cd metatrader-automation
composer install
````

### How to run

#### Backtest generate reports command

````bash
php .\bin\console metatrader:backtest:generate --help
````

#### Backtest import reports command

````bash
php .\bin\console metatrader:backtest:import --help
````
