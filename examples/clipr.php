#!/usr/bin/env php
<?php

# kw_clipr - starter for unix systems
# copy this file somewhere in your project tree and change the relevant parts

if (PHP_SAPI !== 'cli') {
    die('This script can be run only from *nix CLI');
}

# bootstrapping - target your own init
include_once '_app/config/init.php';

# set base for searching the files
$cwd = false !== getcwd() ? getcwd() : __DIR__ ;
\kalanis\kw_input\Loaders\CliEntry::setBasicPath($cwd);

try {
    $inputs = new \kalanis\kw_input\Inputs();
    $inputs->setSource($argv)->loadEntries();
    $clipr = new \kalanis\kw_clipr\Clipr(
        \kalanis\kw_clipr\Loaders\CacheLoader::init(
            \kalanis\kw_clipr\Loaders\MultiLoader::init()
            ->addLoader(
                new \kalanis\kw_clipr\Loaders\KwLoader([
                    '\clipr' => [__DIR__, 'vendor', 'kalanis', 'kw_clipr', 'run'],
                ])
//            )->addLoader( // if you want DI add this one
//                new \kalanis\kw_clipr\Loaders\DiLoader(
//                    (new \YourApplication\ContainerFactory())->create(),
//                    ['\YourApp\Clipr' => [__DIR__, '_app', 'Clipr']]  # just example where to find your tasks
//                )
            )
        ),
        new kalanis\kw_clipr\Clipr\Sources(),
        new kalanis\kw_input\Filtered\Variables($inputs)
    );

    exit($clipr->run());
} catch (\kalanis\kw_clipr\Tasks\SingleTaskException $ex) {
    echo $ex->getMessage() . PHP_EOL;
    exit($ex->getCode() ?: 1);
} catch (\Throwable $ex) {
    echo get_class($ex) . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ':' . $ex->getLine() . PHP_EOL;
    echo "Stack trace:" . PHP_EOL;
    echo $ex->getTraceAsString() . PHP_EOL;
    exit($ex->getCode() ?: 1);
}

# as last step make this file executable
