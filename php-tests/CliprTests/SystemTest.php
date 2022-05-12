<?php

namespace RecordsTests;


use CommonTestClass;
use kalanis\kw_clipr\Clipr;
use kalanis\kw_clipr\CliprException;


class SystemTest extends CommonTestClass
{
    /**
     * @throws CliprException
     */
    public function testSimple()
    {
        $lib = new Clipr();
        $lib->addPath('clipr', implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'run']));
        $this->assertNotEmpty($lib);
        $lib->run([
            '--no-color',
            '--no-headers',
            '--output-file=/tmp/clipr_test_out.txt',
        ]);

        @unlink('/tmp/clipr_test_out.txt');
    }
}
