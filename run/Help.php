<?php

namespace clipr;


use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Output\TPrettyTable;
use kalanis\kw_clipr\Tasks\ATask;
use kalanis\kw_clipr\Tasks\Params;
use kalanis\kw_clipr\Tasks\TaskFactory;


class Help extends ATask
{
    use TPrettyTable;

    public function desc(): string
    {
        return 'Help with Clipr tasks';
    }

    public function process(): void
    {
        $this->writeLn('<yellow><bluebg>+======================+</bluebg></yellow>');
        $this->writeLn('<yellow><bluebg>|       kw_clipr       |</bluebg></yellow>');
        $this->writeLn('<yellow><bluebg>+======================+</bluebg></yellow>');

        try {
            $task = TaskFactory::getInstance()->getTask($this->translator, $this->inputs, 'clipr\Help', 1);
            $this->writeLn('<yellow>Command *' . TaskFactory::getTaskCall($task) . '*</yellow>');
            $this->writeLn('<lcyan>Description:</lcyan>');
            $this->writeLn($task->desc());
            $this->writeLn('<lcyan>Available params:</lcyan>');

            $this->setTableHeaders(['Variable', 'Cli key', 'Short key', 'Current value', 'Description']);
            foreach ($task->getParams()->getAvailableOptions() as $param) {
                /** @var Params\Option $param */
                $this->setTableDataLine([$param->getVariable(), $param->getCliKey(), (string)$param->getShort(), (string)$param->getValue(), $param->getDescription()]);
            }
            $this->dumpTable();

        } catch (CliprException $ex) {
            $this->writeLn($ex->getMessage());
            return;
        }
    }
}
