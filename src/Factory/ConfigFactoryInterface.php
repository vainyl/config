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

namespace Vainyl\Config\Factory;

use Vainyl\Config\ConfigInterface;

/**
 * Interface ConfigFactoryInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
interface ConfigFactoryInterface
{
    /**
     * @param string $name
     * @param array  $configData
     *
     * @return ConfigInterface
     */
    public function createConfig(string $name, array $configData): ConfigInterface;
}
