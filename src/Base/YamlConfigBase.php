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

namespace Vainyl\Config\Base;

use Symfony\Component\Yaml\Yaml;
use Vainyl\Config\ConfigDescriptor;
use Vainyl\Data\AbstractBase;
use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\FileDescriptor;
use Vainyl\Data\SinkInterface;
use Vainyl\Data\SourceInterface;

/**
 * Class YamlConfigBase
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class YamlConfigBase extends AbstractBase implements SourceInterface, SinkInterface
{
    /**
     * @inheritDoc
     */
    public function doGetData(DescriptorInterface $descriptor)
    {
        /**
         * @var ConfigDescriptor $descriptor
         */
        return Yaml::parse(file_get_contents($descriptor->__toString()));
    }

    /**
     * @inheritDoc
     */
    public function doWriteData(DescriptorInterface $descriptor, $data): bool
    {
        return file_put_contents($descriptor->__toString(), Yaml::dump($data));
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'yaml';
    }

    /**
     * @inheritDoc
     */
    public function supports(DescriptorInterface $descriptor): bool
    {
        return $descriptor instanceof FileDescriptor;
    }
}