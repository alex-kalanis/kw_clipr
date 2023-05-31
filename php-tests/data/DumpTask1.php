<?php

namespace data;


use kalanis\kw_clipr\Tasks\ATask;


class DumpTask1 extends ATask
{
    public function process(): int
    {
        return static::STATUS_SIGNAL_USER_1;
    }

    public function desc(): string
    {
        return 'testing task 1';
    }
}
