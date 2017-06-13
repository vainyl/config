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
use Symfony\Component\DependencyInjection\Reference;
use Vainyl\Core\Exception\MissingRequiredFieldException;
use Vainyl\Core\Exception\MissingRequiredServiceException;
use Vainyl\Core\Extension\AbstractCompilerPass;

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
            foreach ($tags as $attributes) {
                if (false === array_key_exists('file', $attributes)) {
                    throw new MissingRequiredFieldException($container, 'config', $attributes, 'file');
                }
                $container
                    ->getDefinition($id)
                    ->setFactory([new Reference('config.storage'), 'getConfig'])
                    ->setArguments([$attributes['file'], new Reference('app.environment')]);
            }
        }
    }
}