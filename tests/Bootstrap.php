<?php
namespace TckImageResizerTest;

use Laminas\Loader\AutoloaderFactory;
use Laminas\Mvc\Service\ServiceManagerConfig;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use RuntimeException;

error_reporting(E_ALL | E_STRICT);
ini_set('date.timezone', 'Europe/Berlin');
chdir(realpath(__DIR__ . '/../../../'));

class Bootstrap
{
    /** @var  ServiceManager */
    protected static $serviceManager;
    /** @var  array */
    protected static $config = [];

    public static function init()
    {
        // Load the user-defined test configuration file, if it exists; otherwise, load
        if (is_readable(__DIR__ . '/TestConfig.php')) {
            $testConfig = include __DIR__ . '/TestConfig.php';
        } else {
            $testConfig = include __DIR__ . '/TestConfig.php.dist';
        }

        $zf2ModulePaths = [];

        if (isset($testConfig['module_listener_options']['module_paths'])) {
            $modulePaths = $testConfig['module_listener_options']['module_paths'];
            foreach ($modulePaths as $modulePath) {
                if (($path = static::findParentPath($modulePath))) {
                    $zf2ModulePaths[] = $path;
                }
            }
        }

        $zf2ModulePaths = implode(PATH_SEPARATOR, $zf2ModulePaths) . PATH_SEPARATOR;
        $zf2ModulePaths .= getenv('ZF2_MODULES_TEST_PATHS') ?:
            (defined('ZF2_MODULES_TEST_PATHS') ? ZF2_MODULES_TEST_PATHS : '');

        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $baseConfig = [
            'module_listener_options' => [
                'module_paths' => explode(PATH_SEPARATOR, $zf2ModulePaths),
            ],
        ];
        
        $config = ArrayUtils::merge($baseConfig, $testConfig);
        
        $serviceManagerConfig = new ServiceManagerConfig();
        $serviceManager = new ServiceManager($serviceManagerConfig->toArray());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
        static::$config = $config;
    }

    /**
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    /**
     * @return array
     */
    public static function getConfig()
    {
        return static::$config;
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (is_readable($vendorPath . '/autoload.php')) {
            $loader = include $vendorPath . '/autoload.php';
        } else {
            $zf2DefaultPath = is_dir($vendorPath . '/ZF2/library') ? $vendorPath . '/ZF2/library' : false;
            $zf2Path = getenv('ZF2_PATH') ?: (defined('ZF2_PATH') ? ZF2_PATH : $zf2DefaultPath);

            if (!$zf2Path) {
                throw new RuntimeException(
                    'Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.'
                );
            }

            include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';

        }

        AutoloaderFactory::factory([
            'Laminas\Loader\StandardAutoloader' => [
                'autoregister_zf' => true,
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ],
            ],
        ]);
    }

    /**
     * @param $path
     * @return bool|string
     */
    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();
