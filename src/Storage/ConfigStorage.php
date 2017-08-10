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
use Vainyl\Config\Factory\ConfigFactoryInterface;
use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Core\Storage\Decorator\AbstractStorageDecorator;
use Vainyl\Core\Storage\StorageInterface;
use Vainyl\Data\SourceInterface;

/**
 * Class ConfigStorage
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigStorage extends AbstractStorageDecorator
{
    private $configFactory;

    private $source;

    /**
     * ConfigStorage constructor.
     *
     * @param StorageInterface       $storage
     * @param SourceInterface        $source
     * @param ConfigFactoryInterface $configFactory
     */
    public function __construct(
        StorageInterface $storage,
        SourceInterface $source,
        ConfigFactoryInterface $configFactory
    ) {
        $this->source = $source;
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
        string $path = ''
    ): ConfigInterface {
        $pathName = sprintf('%s.%s', $configName, $path);
        if ($this->offsetExists($pathName)) {
            return $this->offsetGet($pathName);
        }

        if (false === $this->offsetExists($configName)) {
            $this->offsetSet(
                $configName,
                $config = $this->configFactory->createConfig(
                    $configName,
                    $this->source->getData(new ConfigDescriptor($configName, $environment))
                )
            );
        }
        $this->offsetSet($pathName, $this->offsetGet($configName)->getPath($path));

        return $this->offsetGet($pathName);
    }
}