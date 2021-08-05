#!/usr/bin/env php
<?php

# kw_clipr - starter for unix systems
# copy this file somewhere in your project tree and change the relevant parts

if (PHP_SAPI !== 'cli') {
    die('This script can be run only from *nix CLI');
}

# bootstrapping - target your own init
include_once '_app/config/init.php';

# set base for searching the files - now it's always against path of this script (and usually project root)
\kalanis\kw_input\Loaders\CliEntry::setBasicPath(__DIR__);

# customized Clipr class where the DI is available
class CliprDi extends \kalanis\kw_clipr\Clipr
{
    public function getTaskFactory(): \kalanis\kw_clipr\Tasks\TaskFactory
    {
        return new \kalanis\kw_clipr\Tasks\TaskFactoryDi((new \YourApplication\ContainerFactory())->create());
    }
}


try {
    $clipr = new \kalanis\kw_clipr\Clipr(); // if you want basic one
//    $clipr = new CliprDi(); // if you want DI use customized one
    # change to access basic tasks
    $clipr->addPath('clipr', implode(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'kalanis', 'kw_clipr', 'run']));
    # add your namespaces and paths which target your tasks, not just
    $clipr->addPath('YourApp\\Clipr', implode(DIRECTORY_SEPARATOR, [__DIR__, '_app', 'Clipr'])); // just example

    $clipr->run(array_slice($argv, 1));
} catch (\Exception $ex) {
    echo get_class($ex) . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ':' . $ex->getLine() . PHP_EOL;
    echo "Stack trace:" . PHP_EOL;
    echo $ex->getTraceAsString() . PHP_EOL;
}

# as last step make this file executable
