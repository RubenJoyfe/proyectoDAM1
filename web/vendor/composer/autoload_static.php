<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0e4cdf4fa8d0051bef29b8b022dfa162
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0e4cdf4fa8d0051bef29b8b022dfa162::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0e4cdf4fa8d0051bef29b8b022dfa162::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0e4cdf4fa8d0051bef29b8b022dfa162::$classMap;

        }, null, ClassLoader::class);
    }
}
