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
use Vainyl\Config\Source\ConfigSourceInterface;
use Vainyl\Core\Collection\VectorInterface;
use Vainyl\Core\Queue\PriorityQueueInterface;
use Vainyl\Data\AbstractSource;
use Vainyl\Data\DescriptorInterface;
use Vainyl\Data\Exception\CannotRetrieveDataException;

/**
 * Class ConfigSourceChain
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigSourceChain extends AbstractSource implements ConfigSourceInterface
{
    private $queue;

    private $sources;

    /**
     * ConfigSourceChain constructor.
     *
     * @param PriorityQueueInterface $queue
     * @param VectorInterface        $vector
     */
    public function __construct(PriorityQueueInterface $queue, VectorInterface $vector)
    {
        $this->queue = $queue;
        $this->sources = $vector;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'config';
    }

    /**
     * @param int                   $priority
     * @param ConfigSourceInterface $configSource
     *
     * @return ConfigSourceChain
     */
    public function addSource(int $priority, ConfigSourceInterface $configSource): ConfigSourceChain
    {
        $this->queue->enqueue($configSource, $priority);

        return $this->configure();
    }

    /**
     * @return ConfigSourceChain
     */
    public function configure(): ConfigSourceChain
    {
        $queue = clone $this->queue;
        $this->sources->clear();

        while (false === $queue->valid()) {
            $this->sources->push($queue->dequeue());
        }

        return $this;
    }

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
        /**
         * @var ConfigSourceInterface $source
         */
        foreach ($this->sources as $source) {
            if (null === ($configData = $source->getData($descriptor))) {
                continue;
            }

            return $configData;
        }

        throw new CannotRetrieveDataException($this, $descriptor);
    }
}