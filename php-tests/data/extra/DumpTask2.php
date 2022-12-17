<?php

namespace data\extra;


use kalanis\kw_clipr\Tasks\ATask;


class DumpTask2 extends ATask
{
    public function process(): int
    {
        return self::STATUS_SIGNAL_USER_2;
    }

    public function desc(): string
    {
        return 'testing task 2';
    }
}
