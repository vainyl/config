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

use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\SourceInterface;

/**
 * Interface ConfigSourceInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
interface ConfigSourceInterface extends SourceInterface
{
    /**
     * @param DescriptorInterface $descriptor
     *
     * @return array
     */
    public function getData(DescriptorInterface $descriptor): ?array;
}