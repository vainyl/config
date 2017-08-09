<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   config
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\Config\Factory;

use Vainyl\Config\Config;
use Vainyl\Config\ConfigInterface;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\Core\Storage\StorageInterface;

/**
 * Class ConfigFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigFactory extends AbstractIdentifiable implements ConfigFactoryInterface
{
    private $storage;

    /**
     * ConfigFactory constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function createConfig(string $name, array $configData): ConfigInterface
    {
        return new Config($name, (clone $this->storage)->fromArray($configData), $this);
    }
}