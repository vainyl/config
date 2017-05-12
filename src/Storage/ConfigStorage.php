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
use Vainyl\Config\ConfigSourceInterface;
use Vainyl\Config\Factory\ConfigFactoryInterface;
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
     * @param StorageInterface                    $storage
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
     * @param string $configName
     *
     * @return ConfigInterface
     */
    public function getConfig(string $configName): ConfigInterface
    {
        if (false === $this->offsetExists($configName)) {
            $this->offsetSet(
                $configName,
                $this->configFactory->createConfig(
                    $configName,
                    $this->configSource->getData(new ConfigDescriptor($configName))
                )
            );
        }

        return $this->offsetGet($configName);
    }
}