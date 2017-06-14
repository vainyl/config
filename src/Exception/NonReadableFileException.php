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

namespace Vainyl\Config\Exception;

use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\Exception\AbstractSourceException;
use Vainyl\Data\SourceInterface;

/**
 * Class NonReadableFileException
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class NonReadableFileException extends AbstractSourceException
{
    private $descriptor;

    /**
     * NonReadableFileException constructor.
     *
     * @param SourceInterface     $source
     * @param DescriptorInterface $descriptor
     */
    public function __construct(SourceInterface $source, DescriptorInterface $descriptor)
    {
        $this->descriptor = $descriptor;
        parent::__construct($source, sprintf('Cannot read file %s', $descriptor->__toString()));
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_merge(['descriptor' => $this->descriptor], parent::toArray());
    }
}