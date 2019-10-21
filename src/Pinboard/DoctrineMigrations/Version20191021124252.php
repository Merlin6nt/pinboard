<?php

namespace Pinboard\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20191021124252 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `ipm_tag_info` ADD INDEX `iti_c` (`created_at`);");
        $this->addSql("ALTER TABLE `ipm_timer` ADD INDEX `it_c` (`created_at`);");
    }

    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `ipm_tag_info` DROP INDEX `iti_c`;");
        $this->addSql("ALTER TABLE `ipm_timer` DROP INDEX `it_c`;");
    }

}
