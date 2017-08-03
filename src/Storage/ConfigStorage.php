<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   Config
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\Config\Storage;

use Vainyl\Config\ConfigDescriptor;
use Vainyl\Config\ConfigInterface;
use Vainyl\Config\Source\ConfigSourceInterface;
use Vainyl\Config\Factory\ConfigFactoryInterface;
use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Core\Storage\Decorator\AbstractStorageDecorator;
use Vainyl\Core\Storage\StorageInterface;

/**
 * Class ConfigStorage
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigStorage extends AbstractStorageDecorator
{
    private $configFactory;

    private $configSource;

    /**
     * ConfigStorage constructor.
     *
     * @param StorageInterface       $storage
     * @param ConfigSourceInterface  $configSource
     * @param ConfigFactoryInterface $configFactory
     */
    public function __construct(
        StorageInterface $storage,
        ConfigSourceInterface $configSource,
        ConfigFactoryInterface $configFactory
    ) {
        $this->configSource = $configSource;
        $this->configFactory = $configFactory;
        parent::__construct($storage);
    }

    /**
     * @param string               $configName
     * @param EnvironmentInterface $environment
     * @param string               $path
     *
     * @return ConfigInterface
     */
    public function getConfig(
        string $configName,
        EnvironmentInterface $environment,
        string $path = '/'
    ): ConfigInterface {
        $pathName = sprintf('%s.%', $configName, $path);

        if (false === $this->offsetExists($pathName)) {
            $this->offsetSet(
                $pathName,
                $this->configFactory->createConfig(
                    $configName,
                    $this->configSource->getData(new ConfigDescriptor($configName, $environment))
                )->getConfig($path)
            );
        }

        return $this->offsetGet($pathName);
    }
}