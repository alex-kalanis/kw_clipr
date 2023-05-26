<?php

namespace CliprTests;


use CommonTestClass;
use kalanis\kw_clipr\Clipr;
use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Interfaces;
use kalanis\kw_clipr\Loaders\KwLoader;
use kalanis\kw_clipr\Tasks\ATask;
use kalanis\kw_input\Inputs;
use kalanis\kw_input\Filtered\Variables;


class SystemTest extends CommonTestClass
{
    /**
     * @throws CliprException
     */
    public function testSimple(): void
    {
        $inputs = new Inputs();
        $inputs->setSource([
            '--no-color',
            '--no-headers',
            '--output-file=/tmp/clipr_test_out.txt',
        ])->loadEntries();
        $lib = new Clipr(
            new KwLoader([
                'clipr' => [__DIR__, '..', '..', 'run']
            ]),
            new Clipr\Sources(),
            new Variables($inputs)
        );
        $this->assertNotEmpty($lib);
        $this->assertEquals(Interfaces\IStatuses::STATUS_SUCCESS, $lib->run());

        /** @scrutinizer ignore-unhandled */ @unlink('/tmp/clipr_test_out.txt');
    }

    /**
     * @throws CliprException
     */
    public function testNoTask(): void
    {
        $inputs = new Inputs();
        $inputs->setSource([
            '--no-color',
        ])->loadEntries();
        $lib = new Clipr(
            new XFLoader(), // loader which returns no task
            new Clipr\Sources(),
            new Variables($inputs)
        );
        $this->expectException(CliprException::class);
        $this->assertEquals(Interfaces\IStatuses::STATUS_SUCCESS, $lib->run());
    }
}


class XFLoader implements Interfaces\ILoader
{
    public function getTask(string $classFromParam): ?ATask
    {
        return null; // nothing found
    }
}