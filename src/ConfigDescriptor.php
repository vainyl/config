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

use Vainyl\Core\AbstractIdentifiable;
use Vainyl\Data\DescriptorInterface;

/**
 * Class ConfigDescriptor
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigDescriptor extends AbstractIdentifiable implements DescriptorInterface
{
    private $name;

    /**
     * ConfigDescriptor constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->name;
    }
}