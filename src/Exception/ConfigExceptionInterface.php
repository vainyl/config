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

namespace Vainyl\Config\Exception;

use Vainyl\Config\ConfigInterface;
use Vainyl\Core\Exception\CoreExceptionInterface;

/**
 * Interface ConfigExceptionInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
interface ConfigExceptionInterface extends CoreExceptionInterface
{
    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface;
}