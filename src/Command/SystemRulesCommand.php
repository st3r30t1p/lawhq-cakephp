<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use App\Lib\SystemRules;

class SystemRulesCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out("Starting");
        new SystemRules(1);
        new SystemRules(2);
        $io->out("Finished");
    }
}