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
 * Class ConfigSinkCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigSinkCompilerPass extends AbstractCompilerPass
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->has('config.base.chain')) {
            throw new MissingRequiredServiceException($container, 'config.base.chain');
        }

        $definition = $container->getDefinition('config.base.chain');
        foreach ($container->findTaggedServiceIds('config.sink') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (false === array_key_exists('priority', $attributes)) {
                    throw new MissingRequiredFieldException($container, $id, $attributes, 'priority');
                }
                $definition->addMethodCall('addSink', [$attributes['priority'], new Reference($id)]);
            }
        }
    }
}
