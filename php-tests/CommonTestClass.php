<?php

use kalanis\kw_input\Entries\Entry;
use kalanis\kw_input\Interfaces\IEntry;
use PHPUnit\Framework\TestCase;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
    protected function initEntry(string $source, string $key, $value = null): IEntry
    {
        $entry = new Entry();
        return $entry->setEntry($source, $key, $value);
    }
}
