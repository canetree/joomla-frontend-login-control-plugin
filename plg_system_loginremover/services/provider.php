<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.LoginRemover
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\LoginRemover\Extension\LoginRemover;

return new class () implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     */
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new LoginRemover(
                    $container->get(DispatcherInterface::class),
                    (array) PluginHelper::getPlugin('system', 'loginremover')
                );

                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};