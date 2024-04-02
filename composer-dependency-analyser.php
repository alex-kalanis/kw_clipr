<?php

/**
 * Dependency analyzer configuration
 * @link https://github.com/shipmonk-rnd/composer-dependency-analyser
 */

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    // ignore errors on specific packages and paths
    ->ignoreErrorsOnPackageAndPath('psr/container', __DIR__ . '/php-src/Loaders/DiLoader.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->addPathToScan(__DIR__ . '/run', false)
    ->ignoreUnknownClasses(['\kalanis\kw_autoload\DependencyInjection'])
//    ->ignoreErrorsOnPackageAndPath('alex-kalanis/kw_autoload', __DIR__ . '/php-src/Loaders/KwDiLoader.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
;
