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

namespace Vainyl\Config\Chain;

use Vainyl\Config\ConfigDescriptor;
use Vainyl\Core\Collection\VectorInterface;
use Vainyl\Core\Queue\PriorityQueueInterface;
use Vainyl\Data\AbstractBase;
use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\Exception\CannotRetrieveDataException;
use Vainyl\Data\SinkInterface;
use Vainyl\Data\SourceInterface;

/**
 * Class ConfigBaseChain
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigBaseChain extends AbstractBase implements SourceInterface, SinkInterface
{
    private $sourceQueue;

    private $sinkQueue;

    private $sinks;

    private $sources;

    /**
     * ConfigBaseChain constructor.
     *
     * @param PriorityQueueInterface $sourceQueue
     * @param VectorInterface        $sources
     * @param PriorityQueueInterface $sinkQueue
     * @param VectorInterface        $sinks
     */
    public function __construct(
        PriorityQueueInterface $sourceQueue,
        VectorInterface $sources,
        PriorityQueueInterface $sinkQueue,
        VectorInterface $sinks
    ) {
        $this->sourceQueue = $sourceQueue;
        $this->sources = $sources;
        $this->sinkQueue = $sinkQueue;
        $this->sinks = $sinks;
    }

    /**
     * @param int           $priority
     * @param SinkInterface $sink
     *
     * @return ConfigBaseChain
     */
    public function addSink(int $priority, SinkInterface $sink): ConfigBaseChain
    {
        $this->sinkQueue->enqueue($sink, $priority);

        return $this->configure();
    }

    /**
     * @param int             $priority
     * @param SourceInterface $source
     *
     * @return ConfigBaseChain
     */
    public function addSource(int $priority, SourceInterface $source): ConfigBaseChain
    {
        $this->sourceQueue->enqueue($source, $priority);

        return $this->configure();
    }

    /**
     * @return ConfigBaseChain
     */
    public function configure(): ConfigBaseChain
    {
        $queue = clone $this->sourceQueue;
        $this->sources->clear();

        while (false === $queue->valid()) {
            $this->sources->push($queue->dequeue());
        }

        $queue = clone $this->sinkQueue;
        $this->sinks->clear();

        while (false === $queue->valid()) {
            $this->sinks->push($queue->dequeue());
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function doGetData(DescriptorInterface $descriptor)
    {
        foreach ($this->sources as $source) {
            if (null === ($configData = $source->getData($descriptor))) {
                continue;
            }

            return $configData;
        }

        throw new CannotRetrieveDataException($this, $descriptor);
    }

    /**
     * @inheritDoc
     */
    public function doWriteData(DescriptorInterface $descriptor, $data) : bool
    {
        $result = true;
        foreach ($this->sinks as $sink) {
            if (false === $sink->support($descriptor)) {
                continue;
            }

            $result = $result & $sink->write($descriptor, $data);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'config';
    }

    /**
     * @inheritDoc
     */
    public function supports(DescriptorInterface $descriptor): bool
    {
        return $descriptor instanceof ConfigDescriptor;
    }
}