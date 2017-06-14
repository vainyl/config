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
use Symfony\Component\DependencyInjection\Definition;
use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Core\Extension\AbstractExtension;
use Vainyl\Core\Exception\MissingRequiredFieldException;

/**
 * Class ConfigExtension
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class ConfigExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container): AbstractExtension
    {
        foreach ($configs as $config) {
            if (false === array_key_exists('config', $config)) {
                continue;
            }

            $config = $config['config'];
            if (false === array_key_exists('factory', $config)) {
                throw new MissingRequiredFieldException($container, 'config', $config, 'factory');
            }

            $factoryConfig = $config['factory'];
            if (false === array_key_exists('factory', $config)) {
                throw new MissingRequiredFieldException($container, 'config', $factoryConfig, 'class');
            }
            $arguments = $factoryConfig['arguments'] ?? [];
            $container
                ->setDefinition('config.factory', new Definition($config['class'], $arguments));
        }

        $container
            ->addCompilerPass(new ConfigCompilerPass())
            ->addCompilerPass(new ConfigSourceCompilerPass());

        return parent::load($configs, $container);
    }
}