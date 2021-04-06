<?php

namespace RecordsTests;


use CommonTestClass;
use kalanis\kw_clipr\Clipr\Paths;
use kalanis\kw_clipr\CliprException;


class PathsTest extends CommonTestClass
{
    public function testSimple()
    {
        $instance1 = Paths::getInstance();
        $this->assertInstanceOf('\kalanis\kw_clipr\Clipr\Paths', $instance1);
        $instance2 = Paths::getInstance();
        $this->assertInstanceOf('\kalanis\kw_clipr\Clipr\Paths', $instance2);
        $this->assertTrue($instance1 === $instance2);
    }

    public function testPathNotKnow()
    {
        $instance = Paths::getInstance();
        $this->expectException(CliprException::class);
        $instance->addPath('testing_fail', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data_fail');
        $instance->clearPaths();
    }

    public function testPaths()
    {
        $instance = Paths::getInstance();
        $ptRun = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'run';
        $ptData = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
        $instance->addPath('clipr', $ptRun);
        $instance->addPath('testing', $ptData);
        $this->assertEquals($ptRun, $instance->getPaths()['clipr']);
        $this->assertEquals($ptData, $instance->getPaths()['testing']);
        $instance->clearPaths();
    }

    /**
     * @param string $classPath
     * @param string $namespace
     * @param string $translatedPath
     * @dataProvider classToRealProvider
     */
    public function testClassToReal(string $classPath, string $namespace, string $translatedPath)
    {
        $this->addPaths(true);
        $instance = Paths::getInstance();
        $this->assertEquals($translatedPath, $instance->classToRealFile($classPath, $namespace));
        $instance->clearPaths();
    }

    public function classToRealProvider()
    {
        return [
            ['clipr/any/task', 'clipr', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'task'],
            ['clipr\\any\\task.php', 'clipr', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'task'],
            ['clipr:any:task', 'clipr', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'task'],
        ];
    }

    /**
     * @param string $dir
     * @param string $file
     * @param string|null $translatedClass
     * @dataProvider realToClassProvider
     */
    public function testRealToClass(string $dir, string $file, ?string $translatedClass)
    {
        $this->addPaths(true);
        $instance = Paths::getInstance();
        $this->assertEquals($translatedClass, $instance->realFileToClass($dir, $file));
        $instance->clearPaths();
    }

    public function realToClassProvider()
    {
        $this->addPaths(true);
        $paths = Paths::getInstance()->getPaths();
        return [
            [reset($paths) . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'other', 'help.php', 'clipr\any\other\help'],
            [next($paths) . DIRECTORY_SEPARATOR . 'another', 'task', 'testing\another\task'],
            ['not_in_paths', 'task', null],
        ];
    }
}
