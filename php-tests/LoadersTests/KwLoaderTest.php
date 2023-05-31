<?php

namespace LoadersTests;


use kalanis\kw_clipr\CliprException;
use kalanis\kw_clipr\Interfaces\IStatuses;
use kalanis\kw_clipr\Loaders\KwLoader;
use kalanis\kw_clipr\Tasks\ATask;
use ReflectionException;


class KwLoaderTest extends ALoaderTest
{
    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testOk1(): void
    {
        $lib = new KwLoader([
            'data' => [__DIR__, '..', 'data']
        ]);
        $class = $lib->getTask('data\DumpTask1');
        $this->assertNotNull($class);
        $this->assertInstanceOf(ATask::class, $class);
        $this->assertEquals('testing task 1', $class->desc());
        $this->assertEquals(IStatuses::STATUS_SIGNAL_USER_1, $class->process());
    }

    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testOk2(): void
    {
        $lib = new KwLoader([
            'data' => [__DIR__, '..', 'data']
        ]);
        $class = $lib->getTask('\data\DumpTask1.php');
        $this->assertNotNull($class);
        $this->assertInstanceOf(ATask::class, $class);
        $this->assertEquals('testing task 1', $class->desc());
        $this->assertEquals(IStatuses::STATUS_SIGNAL_USER_1, $class->process());
    }

    /**
     * @throws CliprException
     */
    public function testDirIs(): void
    {
        $lib = new KwLoader([
            'none-can' => [__DIR__]
        ]);
        $this->assertNotEmpty($lib->getPaths());
    }

    /**
     * @throws CliprException
     */
    public function testDirIsNot(): void
    {
        $lib = new KwLoader();
        $this->assertEmpty($lib->getPaths());
    }

    /**
     * @throws CliprException
     */
    public function testDirFail(): void
    {
        $this->expectException(CliprException::class);
        new KwLoader([
            'none-available' => ['not-a-path']
        ]);
    }

    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testFileFail(): void
    {
        $lib = new KwLoader([
            'test' => [__DIR__, '..', 'data', 'extra']
        ]);
        $this->assertNull($lib->getTask('test\noneAbove'));
    }

    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testFileCanNotCreate(): void
    {
        $lib = new KwLoader([
            'data' => [__DIR__, '..', 'data']
        ]);
        $this->assertNull($lib->getTask('data\extra\ADumpTask3'));
    }

    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testFileNotInside(): void
    {
        $lib = new KwLoader([
            'data' => [__DIR__, '..', 'data']
        ]);
        $this->assertNull($lib->getTask('data\extra\DumpTask4'));
    }

    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testFileNotInPath(): void
    {
        $lib = new KwLoader([
            'data' => [__DIR__, '..', 'data']
        ]);
        $this->assertNull($lib->getTask('extra\DumpTask'));
    }

    /**
     * @throws CliprException
     * @throws ReflectionException
     */
    public function testFileNotInstance(): void
    {
        $lib = new KwLoader([
            'data' => [__DIR__, '..', 'data']
        ]);
        $this->expectException(CliprException::class);
        $lib->getTask('data\extra\FDumpTask5');
    }
}
