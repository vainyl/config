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

use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\FileDescriptor;

/**
 * Class ConfigDescriptor
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigDescriptor extends FileDescriptor implements DescriptorInterface
{
    /**
     * ConfigDescriptor constructor.
     *
     * @param string               $name
     * @param EnvironmentInterface $environment
     */
    public function __construct(string $name, EnvironmentInterface $environment)
    {
        parent::__construct($environment->getConfigDirectory() . DIRECTORY_SEPARATOR . $name);
    }
}