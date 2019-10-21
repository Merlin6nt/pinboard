<?php

namespace Pinboard\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20191018165422 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `ipm_request` (
              `id` int(11) NOT NULL DEFAULT '0',
              `hostname` varchar(32) DEFAULT NULL,
              `req_count` int(11) DEFAULT NULL,
              `server_name` varchar(64) DEFAULT NULL,
              `script_name` varchar(128) DEFAULT NULL,
              `doc_size` float DEFAULT NULL,
              `mem_peak_usage` float DEFAULT NULL,
              `req_time` float DEFAULT NULL,
              `ru_utime` float DEFAULT NULL,
              `ru_stime` float DEFAULT NULL,
              `timers_cnt` int(11) DEFAULT NULL,
              `status` int(11) DEFAULT NULL,
              `memory_footprint` float DEFAULT NULL,
              `schema` varchar(16) DEFAULT NULL,
              `tags_cnt` int(11) DEFAULT NULL,
              `tags` varchar(1024) DEFAULT NULL,
              `timestamp` int(11) DEFAULT NULL,
              KEY `ir_sss` (`server_name`,`script_name`,`status`),
              KEY `ir_shst` (`server_name`,`hostname`,`script_name`,`timestamp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `ipm_request`;");
    }
}
