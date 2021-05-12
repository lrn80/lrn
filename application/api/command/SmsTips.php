<?php


namespace app\api\command;


use app\api\model\News;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class SmsTips extends Command
{
    protected function configure()
    {
        $this->setName('sms_tips')->setDescription('Here is the remark ');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln("TestCommand:");
    }
}