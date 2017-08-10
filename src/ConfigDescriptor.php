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
use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Data\DescriptorInterface;

/**
 * Class ConfigDescriptor
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigDescriptor extends AbstractIdentifiable implements DescriptorInterface
{
    private $name;

    private $cached;

    private $environment;

    /**
     * ConfigDescriptor constructor.
     *
     * @param string               $name
     * @param EnvironmentInterface $environment
     * @param bool                 $cached
     */
    public function __construct(string $name, EnvironmentInterface $environment, bool $cached = false)
    {
        $this->name = $name;
        $this->environment = $environment;
        $this->cached = $cached;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getFileName();
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        if (false === $this->cached) {
            return $this->environment->getConfigDirectory() . DIRECTORY_SEPARATOR . $this->name;
        }

        return $this->environment->getCacheDirectory() . DIRECTORY_SEPARATOR . $this->name;
    }

    /**
     * @return ConfigDescriptor
     */
    public function invert(): ConfigDescriptor
    {
        $this->cached = !$this->cached;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        return is_readable($this->getFileName());
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        return is_writable($this->getFileName());
    }
}