<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit209bb31a5decc9e07226def1eb0ceaf8
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit209bb31a5decc9e07226def1eb0ceaf8', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit209bb31a5decc9e07226def1eb0ceaf8', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit209bb31a5decc9e07226def1eb0ceaf8::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
