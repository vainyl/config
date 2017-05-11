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

use Ds\PriorityQueue;
use Ds\Vector;
use Vainyl\Config\ConfigDescriptor;
use Vainyl\Config\ConfigSourceInterface;
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
    private $sources;

    private $queue;

    /**
     * ConfigSourceChain constructor.
     */
    public function __construct()
    {
        $this->sources = new Vector();
        $this->queue = new PriorityQueue();
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
        $this->queue->push($configSource, $priority);

        return $this->configure();
    }

    /**
     * @return ConfigSourceChain
     */
    public function configure(): ConfigSourceChain
    {
        $queue = clone $this->queue;
        $list = new Vector();

        while (false === $queue->isEmpty()) {
            $list->push($queue->pop());
        }

        $this->sources = $list;

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
    public function doGetData(DescriptorInterface $descriptor): array
    {
        /**
         * @var ConfigSourceInterface $source
         */
        foreach (($copy = $this->sources->copy()) as $source) {
            if (null === ($configData = $source->getData($descriptor))) {
                continue;
            }

            return $configData;
        }

        throw new CannotRetrieveDataException($this, $descriptor);
    }
}