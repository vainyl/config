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

namespace Vainyl\Config\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vainyl\Core\Extension\AbstractCompilerPass;
use Vainyl\Core\Exception\MissingRequiredFieldException;
use Vainyl\Core\Exception\MissingRequiredServiceException;

/**
 * Class ConfigSourceCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigSourceCompilerPass extends AbstractCompilerPass
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->has('config.source.chain')) {
            throw new MissingRequiredServiceException($container, 'config.source.chain');
        }

        $definition = $container->getDefinition('config.source.chain');
        foreach ($container->findTaggedServiceIds('config.source') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (false === array_key_exists('priority', $attributes)) {
                    throw new MissingRequiredFieldException($container, $id, $attributes, 'priority');
                }
                $definition->addMethodCall('addSource', [$attributes['priority'], new Reference($id)]);
            }
        }
    }
}
