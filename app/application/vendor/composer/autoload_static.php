<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit64386c4d10c30ca3e7cd5e54703b86a6
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'Yurun\\Until\\' => 12,
            'Yurun\\OAuthLogin\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Yurun\\Until\\' => 
        array (
            0 => __DIR__ . '/..' . '/yurunsoft/yurun-event/Src',
            1 => __DIR__ . '/..' . '/yurunsoft/yurun-http',
        ),
        'Yurun\\OAuthLogin\\' => 
        array (
            0 => __DIR__ . '/..' . '/yurunsoft/yurun-oauth-login/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit64386c4d10c30ca3e7cd5e54703b86a6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit64386c4d10c30ca3e7cd5e54703b86a6::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
