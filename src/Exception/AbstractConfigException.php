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

namespace Vainyl\Config\Exception;

use Vainyl\Config\ConfigInterface;
use Vainyl\Core\Exception\AbstractCoreException;

/**
 * Class AbstractConfigException
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
abstract class AbstractConfigException extends AbstractCoreException implements ConfigExceptionInterface
{
    private $config;

    /**
     * AbstractConfigException constructor.
     *
     * @param ConfigInterface $config
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct(ConfigInterface $config, string $message, int $code = 500, \Throwable $previous = null)
    {
        $this->config = $config;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_merge(['config' => $this->config], parent::toArray());
    }
}