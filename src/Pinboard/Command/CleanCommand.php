<?php
namespace Pinboard\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class CleanCommand extends Command
{
    protected $params;
    protected $output;

    protected function configure()
    {
        $this
            ->setName('clean')
            ->setDescription('Clean old data from tables')
        ;
    }

    protected function currentTime()
    {
        $now = new \DateTime();
        return $now->format('Y-m-d H:i:s');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->app = $this->getApplication()->getSilex();
        $this->app->boot();
        $this->params = $this->app['params'];
        $this->output = $output;

        $db = $this->app['db'];

        $output->writeln('<comment>[' . $this->currentTime() . ']</comment> <info>Starting clean</info>');

        try {
            $db->connect();
        }
        catch(\PDOException $e) {
            $output->writeln('<error>Can\'t connect to MySQL server</error>');

            return;
        }

        $delta = new \DateInterval(isset($this->params['records_lifetime']) ? $this->params['records_lifetime'] : 'P1M');
        $date = new \DateTime();
        $date->sub($delta);

        $params = array(
            'created_at' => $date->format('Y-m-d H:i:s'),
        );

        $tablesForClear = array(
            "ipm_report_2_by_hostname_and_server",
            "ipm_report_by_hostname",
            "ipm_report_by_hostname_and_server",
            "ipm_report_by_server_name",
            "ipm_req_time_details",
            "ipm_mem_peak_usage_details",
            "ipm_status_details",
            "ipm_cpu_usage_details",
            "ipm_timer",
            "ipm_tag_info",
        );


        foreach ($tablesForClear as $value) {
            $output->write('<comment>[' . $this->currentTime() . ']</comment> <info>Clean </info>"' . $value . '"<comment>....</comment>');
            $sql = '
            DELETE
            FROM
                ' . $value . '
            WHERE
                created_at < :created_at
            ;';

            if ($db->executeQuery($sql, $params)->closeCursor()) {
                $output->writeln('<info>Done</info>');
            }
        }

        $output->writeln('<comment>[' . $this->currentTime() . ']</comment> <info>Finished</info>');
    }
}
