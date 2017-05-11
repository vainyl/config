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

namespace Vainyl\Config;

use Vainyl\Config\Exception\UnknownConfigPathException;
use Vainyl\Config\Factory\ConfigFactoryInterface;
use Vainyl\Core\Storage\Decorator\AbstractStorageDecorator;
use Vainyl\Core\Storage\StorageInterface;

/**
 * Class Config
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class Config extends AbstractStorageDecorator implements ConfigInterface
{
    private $name;

    private $configFactory;

    /**
     * Config constructor.
     *
     * @param string                 $name
     * @param StorageInterface       $configData
     * @param ConfigFactoryInterface $configFactory
     */
    public function __construct(string $name, StorageInterface $configData, ConfigFactoryInterface $configFactory)
    {
        $this->name = $name;
        $this->configFactory = $configFactory;
        parent::__construct($configData);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(string $path): ConfigInterface
    {
        $configData = $this;
        foreach (explode('.', $path) as $pathElement) {
            if (false === $configData->offsetExists($pathElement)) {
                throw new UnknownConfigPathException($this, $path, $pathElement);
            }
            $configData = $configData->offsetGet($pathElement);
        }

        return $this->configFactory->createConfig(sprintf('%s.%s', $this->getName(), $path), $configData);
    }
}
