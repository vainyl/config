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

namespace Vainyl\Config\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vainyl\Core\Extension\AbstractCompilerPass;
use Vainyl\Core\Exception\MissingRequiredServiceException;

/**
 * Class ConfigCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigCompilerPass extends AbstractCompilerPass
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->has('config.storage')) {
            throw new MissingRequiredServiceException($container, 'config.storage');
        }

        foreach ($container->findTaggedServiceIds('config') as $id => $tags) {
            $container
                ->getDefinition($id)
                ->setFactory(['config.storage', 'getConfig'])
                ->setArguments(str_replace('config.', '', 1));
        }
    }
}