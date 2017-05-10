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

use Ds\Map;
use Vainyl\Config\Config;
use Vainyl\Config\ConfigInterface;
use Vainyl\Core\AbstractIdentifiable;

/**
 * Class ConfigFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigFactory extends AbstractIdentifiable implements ConfigFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createConfig(string $name, array $configData): ConfigInterface
    {
        return new Config($name, new Map($configData), $this);
    }
}