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

namespace Vainyl\Config\Source;

use Symfony\Component\Yaml\Yaml;
use Vainyl\Config\ConfigDescriptor;
use Vainyl\Config\Exception\NonReadableFileException;
use Vainyl\Data\AbstractSource;
use Vainyl\Data\DescriptorInterface;

/**
 * Class YamlConfigSource
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class YamlConfigSource extends AbstractSource implements ConfigSourceInterface
{
    /**
     * @inheritDoc
     */
    public function supports(DescriptorInterface $descriptor): bool
    {
        return $descriptor instanceof ConfigDescriptor;
    }

    /**
     * @inheritDoc
     */
    public function doGetData(DescriptorInterface $descriptor)
    {
        if (false === is_readable($descriptor->__toString())) {
            throw new NonReadableFileException($this, $descriptor);
        }

        /**
         * @var ConfigDescriptor $descriptor
         */
        return Yaml::parse(file_get_contents($descriptor->__toString()));
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'yaml';
    }
}