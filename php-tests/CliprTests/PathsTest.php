<?php

namespace CliprTests;


use CommonTestClass;
use kalanis\kw_clipr\Clipr\Paths;
use kalanis\kw_clipr\CliprException;


class PathsTest extends CommonTestClass
{
//    public function testSimple(): void
//    {
//        $instance1 = XPaths::getInstance();
//        $this->assertInstanceOf(Paths::class, $instance1);
//        $instance2 = XPaths::getInstance();
//        $this->assertInstanceOf(Paths::class, $instance2);
//        $this->assertTrue($instance1 === $instance2);
//        XPaths::clearInstance();
//        $instance3 = XPaths::getInstance();
//        $this->assertInstanceOf(Paths::class, $instance3);
//        $this->assertTrue($instance1 !== $instance3);
//    }

//    /**
//     * @throws CliprException
//     */
//    public function testPathNotKnow(): void
//    {
//        $instance = Paths::getInstance();
//        $this->expectException(CliprException::class);
//        $instance->addNamespace('testing_fail', [__DIR__, '..', 'data_fail']);
//        $instance->clearPaths();
//    }

//    /**
//     * @throws CliprException
//     */
//    public function testPaths(): void
//    {
//        $instance = Paths::getInstance();
//        $ptRun = [__DIR__, '..', '..', 'run'];
//        $ptData = [__DIR__, '..', 'data'];
//        $instance->addNamespace('clipr', $ptRun);
//        $instance->addNamespace('testing', $ptData);
//        $this->assertEquals($ptRun, $instance->getPaths()['clipr']);
//        $this->assertEquals($ptData, $instance->getPaths()['testing']);
//        $instance->clearPaths();
//    }

//    /**
//     * @param string $classPath
//     * @param string $namespace
//     * @param string $translatedPath
//     * @throws CliprException
//     * @dataProvider classToRealProvider
//     */
//    public function testClassToReal(string $classPath, string $namespace, string $translatedPath): void
//    {
//        $this->addPaths(true);
//        $instance = Paths::getInstance();
//        $this->assertEquals($translatedPath, $instance->classToRealFile($classPath, $namespace));
//        $instance->clearPaths();
//    }

//    public function classToRealProvider(): array
//    {
//        return [
//            ['clipr/any/task', 'clipr', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'task'],
//            ['clipr\\any\\task.php', 'clipr', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'task'],
//            ['clipr:any:task', 'clipr', DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'task'],
//        ];
//    }

//    /**
//     * @param string $dir
//     * @param string $file
//     * @param string|null $translatedClass
//     * @throws CliprException
//     * @dataProvider realToClassProvider
//     */
//    public function testRealToClass(string $dir, string $file, ?string $translatedClass): void
//    {
////        $this->addPaths(true);
//        $instance = Paths::getInstance();
//        $this->assertEquals($translatedClass, $instance->realFileToClass([], $dir, $file));
////        $instance->clearPaths();
//    }
//
//    /**
//     * @throws CliprException
//     * @return array<array<string|null>>
//     */
//    public function realToClassProvider(): array
//    {
////        $this->addPaths(true);
////        $paths = Paths::getInstance()->getPaths();
//        return [
//            [implode(DIRECTORY_SEPARATOR, reset($paths)) . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'other', 'help.php', 'clipr\any\other\help'],
//            [implode(DIRECTORY_SEPARATOR, next($paths)) . DIRECTORY_SEPARATOR . 'another', 'task', 'testing\another\task'],
//            ['not_in_paths', 'task', null],
//        ];
//    }
}


class XPaths extends Paths
{
    public static function clearInstance(): void
    {
        static::$instance = null;
    }
}