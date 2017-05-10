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

/**
 * Class UnknownConfigPathException
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class UnknownConfigPathException extends AbstractConfigException
{
    private $path;

    private $pathElement;

    /**
     * UnknownConfigPathException constructor.
     *
     * @param ConfigInterface $config
     * @param string          $path
     * @param string          $pathElement
     */
    public function __construct(ConfigInterface $config, string $path, string $pathElement)
    {
        parent::__construct(
            $config,
            sprintf(
                'Cannot find config path %s while traversing %s in config %s',
                $pathElement,
                $path,
                $config->getName()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_merge(['path' => $this->path, 'path_element' => $this->pathElement], parent::toArray());
    }
}