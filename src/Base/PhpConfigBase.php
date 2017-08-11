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

use Vainyl\Config\ConfigDescriptor;
use Vainyl\Data\AbstractBase;
use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\FileDescriptor;

/**
 * Class PhpConfigBase
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PhpConfigBase extends AbstractBase
{
    /**
     * @param ConfigDescriptor $descriptor
     *
     * @return array
     */
    public function doGetData(DescriptorInterface $descriptor)
    {
        return require $descriptor->__toString();
    }

    /**
     * @inheritDoc
     */
    public function doWriteData(DescriptorInterface $descriptor, $data): bool
    {
        return file_put_contents($descriptor->__toString(), var_export($data, true));
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'php';
    }

    /**
     * @inheritDoc
     */
    public function supports(DescriptorInterface $descriptor): bool
    {
        return $descriptor instanceof FileDescriptor;
    }
}