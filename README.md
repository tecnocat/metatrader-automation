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

#### Backtest generate reports command 💻 (‼️ not yet completed ‼️)

````bash
php bin/console metatrader:backtest:generate --help
````

#### Backtest import reports command 💻

````bash
php bin/console metatrader:backtest:import --help
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
    data_path: C:\Path\To\Data\Directory # Usually C:\Users\You\AppData\Roaming\MetaQuotes\Terminal
    expert_advisors:
      YourExpertAdvisorName:
        active: true # Required
        limit: 10
      AnotherExpertAdvisorName:
        active: false # Required
        foo: bar
````

### PHP Expert Advisor implementation 🤖

````php
<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\EntityInterface;

class YourExpertAdvisorName extends AbstractExpertAdvisor
{
    public function generateBacktestReportName(EntityInterface $backtestEntity): \Generator
    {
        // You can fetch Expert Advisor parameters calling $this->getParameters() 
        $limit = $this->getParameters()->get('limit');

        // Your iterations parameters to generate a unique backtest report name
        // At the moment iterator_to_array is required because 2 level of Generators
        $iterations = [

            // Date range iterator -> (from date, to date, step months)
            iterator_to_array($this->dateRangeIterator(new \DateTime('2013-01-01'), new \DateTime('2013-06-01'), 3)),
            // This will generate a date range array like this:
            // ['from' => '2013.01.01', 'to' => '2013.04.01']
            // ['from' => '2013.02.01', 'to' => '2013.05.01']
            // ['from' => '2013.03.01', 'to' => '2013.06.01']
            // ['from' => '2013.04.01', 'to' => '2013.06.01']
            // ['from' => '2013.05.01', 'to' => '2013.06.01']

            // Min max iterator -> (parameter name, [minimum, maximum, step])
            iterator_to_array($this->minMaxIterator('ticks', ['min' => 100, 'max' => 300, 'step' => 50])),
            // This will generate a range array like this:
            // ['ticks' => 100]
            // ['ticks' => 150]
            // ['ticks' => 200]
            // ['ticks' => 250]
            // ['ticks' => 300]

            // Simple iterator -> (parameter name, [elements])
            iterator_to_array($this->simpleIterator('period', ['M15', 'H4', 'D1'])),
            // This will generate a range array like this:
            // ['period' => 'M15']
            // ['period' => 'H4']
            // ['period' => 'D1']
        ];

        // Your main iteration loop, this generates a cartesian combination of all iterations
        foreach ($this->iterate($iterations) as $iteration)
        {
            yield $this->getBacktestReportName($iteration);
            // This will generate a report names like this:
            // M15-2013.01.01-2013.04.01-t100.html
            // M15-2013.02.01-2013.05.01-t100.html
            // M15-2013.03.01-2013.06.01-t100.html
            // etc...

            // H4-2013.01.01-2013.04.01-t100.html
            // H4-2013.02.01-2013.05.01-t100.html
            // H4-2013.03.01-2013.06.01-t100.html
            // etc...

            // D1-2013.01.01-2013.04.01-t100.html
            // D1-2013.02.01-2013.05.01-t100.html
            // D1-2013.03.01-2013.06.01-t100.html
            // etc...
        }
    }

    public function getAlias(): array
    {
        return [
            // PHP name     MT4 EA name (.ex4)
            'iteration' => 'InputIteration',
        ];
    }
}
````

## Development information 🐙

#### Current development 🔥

* Refactor every class object / entity to a Data Transfer Object
* System to handle start / stop of Tick Data Suite during backtest

#### Next steps ✨

* Multiple Expert Advisors and symbols to backtesting at same time
* Analyze database to allow relaunch the already executed tests
* May be store backtest report image in the database or folder
* Put Expert Advisor input parameters inside class as properties
* Backtest report names must include deposit or remove period dates
* Auto improve parameters for expert advisor reading stored backtests
* Refactor error handling, pass event to event in a other event
* Refactor import backtest report and make unnecessary the name

#### Known bugs 🐞

* The import command take too much time between backtest reports
* Date validator is failing when one of the date fields is empty
* The minimum range for dates is a month, but script accept any

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
