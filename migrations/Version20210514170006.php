<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210514170006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backtest_report_entity DROP long_positions_won, DROP loss_trades_percent, DROP maximal_consecutive_loss_count, DROP maximal_consecutive_profit_count, DROP maximum_consecutive_losses_money, DROP maximum_consecutive_wins_money, DROP profit_trades_percent, DROP short_positions_won');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backtest_report_entity ADD long_positions_won DOUBLE PRECISION NOT NULL, ADD loss_trades_percent DOUBLE PRECISION NOT NULL, ADD maximal_consecutive_loss_count INT NOT NULL, ADD maximal_consecutive_profit_count INT NOT NULL, ADD maximum_consecutive_losses_money DOUBLE PRECISION NOT NULL, ADD maximum_consecutive_wins_money DOUBLE PRECISION NOT NULL, ADD profit_trades_percent DOUBLE PRECISION NOT NULL, ADD short_positions_won DOUBLE PRECISION NOT NULL');
    }
}
