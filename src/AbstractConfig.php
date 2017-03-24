<?php
/**
 * Vainylyl
 *
 * PHP Version 7
 *
 * @package   Config
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types = 1);

namespace Vainyl\Config;

use Vainyl\Config\Factory\ConfigFactoryInterface;
use Vainyl\Core\Storage\Exception\UnknownOffsetException;
use Vainyl\Core\Storage\Proxy\AbstractStorageProxy;
use Vainyl\Core\Storage\StorageInterface;

/**
 * Class AbstractConfig
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
abstract class AbstractConfig extends AbstractStorageProxy implements ConfigInterface, ConfigFactoryInterface
{
    private $name;

    /**
     * AbstractConfig constructor.
     *
     * @param string $name
     * @param array  $storage
     */
    public function __construct(string $name, StorageInterface $storage)
    {
        $this->name = $name;
        parent::__construct($storage);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $path
     *
     * @return ConfigInterface
     * @throws UnknownOffsetException
     */
    public function getConfig(string $path): ConfigInterface
    {
        $configData = $this;
        foreach (explode('.', $path) as $pathElement) {
            if (false === $this->offsetExists($pathElement)) {
                throw new UnknownOffsetException($this, $pathElement);
            }
            $configData = $configData[$pathElement];
        }

        return $this->createConfig($this->name . $path, $configData);
    }
}