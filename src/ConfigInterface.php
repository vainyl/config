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

namespace Vainyl\Config;

use Vainyl\Core\NameableInterface;

/**
 * Class ConfigInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
interface ConfigInterface extends NameableInterface, \ArrayAccess
{
    /**
     * @param string $path
     *
     * @return ConfigInterface
     */
    public function getConfig(string $path): ConfigInterface;
}