<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506170812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE backtest_report_entity (id INT AUTO_INCREMENT NOT NULL, absolute_drawdown DOUBLE PRECISION NOT NULL, average_consecutive_losses INT NOT NULL, average_consecutive_wins INT NOT NULL, average_loss_trade DOUBLE PRECISION NOT NULL, average_profit_trade DOUBLE PRECISION NOT NULL, bars_in_test INT NOT NULL, expected_payoff DOUBLE PRECISION NOT NULL, gross_loss DOUBLE PRECISION NOT NULL, gross_profit DOUBLE PRECISION NOT NULL, initial_deposit INT NOT NULL, largest_loss_trade DOUBLE PRECISION NOT NULL, largest_profit_trade DOUBLE PRECISION NOT NULL, long_positions INT NOT NULL, long_positions_won DOUBLE PRECISION NOT NULL, loss_trades INT NOT NULL, loss_trades_percent DOUBLE PRECISION NOT NULL, maximal_consecutive_loss DOUBLE PRECISION NOT NULL, maximal_consecutive_loss_count INT NOT NULL, maximal_consecutive_profit DOUBLE PRECISION NOT NULL, maximal_consecutive_profit_count INT NOT NULL, maximal_drawdown DOUBLE PRECISION NOT NULL, maximum_consecutive_losses INT NOT NULL, maximum_consecutive_losses_money DOUBLE PRECISION NOT NULL, maximum_consecutive_wins INT NOT NULL, maximum_consecutive_wins_money DOUBLE PRECISION NOT NULL, mismatched_charts_errors INT NOT NULL, model VARCHAR(255) NOT NULL, modelling_quality DOUBLE PRECISION NOT NULL, parameters LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', period VARCHAR(255) NOT NULL, profit_factor DOUBLE PRECISION NOT NULL, profit_trades INT NOT NULL, profit_trades_percent DOUBLE PRECISION NOT NULL, relative_drawdown DOUBLE PRECISION NOT NULL, short_positions INT NOT NULL, short_positions_won DOUBLE PRECISION NOT NULL, spread INT NOT NULL, symbol VARCHAR(255) NOT NULL, ticks_modelled INT NOT NULL, total_net_profit DOUBLE PRECISION NOT NULL, total_trades INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE backtest_report_entity');
    }
}
