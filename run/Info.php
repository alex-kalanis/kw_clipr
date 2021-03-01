<?php

namespace clipr;


use kalanis\kw_clipr\Output\TPrettyTable;
use kalanis\kw_clipr\Tasks\ATask;
use kalanis\kw_clipr\Tasks\Params;


class Info extends ATask
{
    use TPrettyTable;

    public function desc(): string
    {
        return 'Info about Clipr and its inputs';
    }

    public function process(): void
    {
        $this->writeLn('<yellow><bluebg>+======================+</bluebg></yellow>');
        $this->writeLn('<yellow><bluebg>|       kw_clipr       |</bluebg></yellow>');
        $this->writeLn('<yellow><bluebg>+======================+</bluebg></yellow>');
        $this->writeLn('<yellow><bluebg>|  Info about system   |</bluebg></yellow>');
        $this->writeLn('<yellow><bluebg>+======================+</bluebg></yellow>');
        $this->setTableHeaders(['local variables', 'cli key', 'current value']);
        foreach ($this->params->getAvailableOptions() as $option) {
            /** @var Params\Option $option */
            $this->setTableDataLine([$option->getVariable(), $option->getCliKey(), $option->getValue()]);
        }
        $this->dumpTable();
    }
}
