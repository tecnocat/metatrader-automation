# Metatrader Automation 📈

This project born due to the need to run many instances of Metatrader 4 to do an automatic backtesting with Tick Data
Suite enabled, and my first php script was totally destroying the PHP OOP concepts and best practices becoming a big
monster, and additionally is taken soo long time to do the backtesting because only use one instance of Metatrader 4

I'm best practise's evangelist, I love hexagonal architecture, CQRS, TDD, DDD, Behat, automatic auto-generated tests
(by code), I like to develop automatic git bots to find and fix many developer mistakes (code, annotations, etc...)
deny or merge PR, and a lot of funny stuff and very clean code practise, but in this project I don't really need the
such power to overload of all this good practises, because is just a console scheduler to run a lot of Metatrader 4
automatic backtests, and I need to set up fast as possible to run and quick develop my Metatrader 4 expert advisors.

## Installation 🧙

````bash
git clone https://github.com/tecnocat/metatrader-automation.git
cd metatrader-automation
composer install
````

### How to run ⁉️

#### Backtest generate reports command 💻

````bash
php bin/console metatrader:backtest:generate --help
````

#### Backtest import reports command 💻

````bash
php bin/console metatrader:backtest:import --help
````

#### Backtest backup reports command 💻

````bash
php bin/console metatrader:backtest:backup --help
````

## Implementation 🌠

### Requirements 🏁

* PHP 7.4.6
* Some PHP extensions enabled on PHP CLI (run composer install to know what)
* Metatrader 4
* An Expert Advisor implemented in `src/Metatrader/Automation/ExpertAdvisor`

### Metatrader 4 Implementation 📊

See `config/services.yaml` to set up all the settings for your Expert Advisors and Metatrader 4 data directory

````yaml
---
parameters:
  metatrader:
    
    # The Metatrader data path usually is C:\Users\You\AppData\Roaming\MetaQuotes\Terminal
    data_path: C:\Path\To\Data\Directory
    
    # List of your Expert Advisors
    expert_advisors:

      # The name of your Expert Advisor must be the same as your ExpertAdvisor PHP class name
      YourExpertAdvisorName:

        # Required
        active: true

        # Allowed formats are: min, max, increment | single value | array
        # NOTE: Booleans must be single quoted
        inputs:

          # The name of the Expert Advisor inputs must be the same as variable in .ex4 file 
          # PHP               MT4 .ex4 
          Ticks: 10, 100, 5 # input int  Ticks         = 0;
          Hedging: 'false'  # input bool Hedging       = false;
          LotMultiplier:    # input int  LotMultiplier = 1  
            - 1
            - 5
            - 15

      # The name of your Expert Advisor must be the same as your ExpertAdvisor PHP class name
      AnotherExpertAdvisorName:

        # Required
        active: false

        # Allowed formats are: min, max, increment | single value | array
        # NOTE: Booleans must be single quoted
        inputs:

          # The name of the Expert Advisor inputs must be the same as variable in .ex4 file 
          # PHP                MT4 .ex4 
          TakeProfit: 'true' # input bool TakeProfit = true;
          StopLoss: 'true'   # input bool StopLoss   = true;
          Lots:              # input int  Lots       = 1
            - 0.01
            - 0.05
            - 0.25
            - 0.60
            - 0.80
````

### PHP Expert Advisor implementation 🤖

````php
<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\EntityInterface;

// The name of your Expert Advisor must be the same as your ExpertAdvisor.ex4 file
class YourExpertAdvisorName extends AbstractExpertAdvisor
{
    public function getIteration(BacktestDTO $backtestDTO): \Generator
    {
        // The system load all iterations for you using Backtest information
        // and config for the current Expert Advisor inputs (services.yaml)
        foreach ($this->loadIterations($backtestDTO) as $iteration)
        {
            // All the input parameters must be prefixed to manage
            $prefix = BacktestReportHelper::INPUTS_PARAMETER_PREFIX;
            
            // Now you can create mutations or new input parameters
            $iteration[$prefix . 'MaxTicks'] = $iteration[$prefix . 'Ticks'] * 2;
            $iteration[$prefix . 'Hedging']  = $iteration[$prefix . 'Ticks'] > 50;

            yield $iteration;
        }
    }
}
````

## Development information 🐙

#### Current development 🔥

* Auto improve parameters for expert advisor reading stored backtests
* Purge the logs between executions to free a lot of space on disk

#### Next steps ✨

* Multiple Expert Advisors and symbols to backtesting at same time
* Analyze database to allow relaunch the already executed tests
* May be store backtest report image in the database or folder
* Refactor error handling, pass event to event in a other event

#### Known bugs 🐞

* The import command take too much time between backtest reports
* Date validator is failing when one of the date fields is empty
* The minimum range for dates is a month, but script accept any
* Many PHPUnit tests are broken due to heavy development, sorry ;-)

#### Already done ✔️

* Prepare command basics with components and workflow events
* System to load Expert Advisor settings based on .yaml files
* Backtest report parser (to future store in database with Doctrine)
* Expert Advisor iterator for each available backtest settings
* Form builder and helper to handle dynamic forms based on entities
* Command to import already executed Metatrader backtest reports
* Clean the Kernel and move the logic to isolated Compiler pass
* Find on data folder for installed Metatrader 4 instances and launch
* Config.ini and ExpertAdvisor.ini files to auto-start up Metatrader 4
* Workflow steps to detect Metatrader 4 instances free to run
* Cluster generator (copy many instances of main Metatrader 4)
* Improve the iteration steps generators to simplify Expert Advisors
* Refactor import backtest report and make unnecessary the name
* Refactor every class object / entity to a Data Transfer Object
* System to handle start / stop of Tick Data Suite during backtest
* Put Expert Advisor input parameters inside class as properties
* Backtest report names must include deposit or remove period dates
* Implement Bartolo Expert Advisor to allow testing multi-strategy
* Backup the Backtest reports and archive all in structured folders
