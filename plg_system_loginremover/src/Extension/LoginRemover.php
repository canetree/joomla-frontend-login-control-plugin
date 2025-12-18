<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.LoginRemover
 * @author      Daniel McNulty - Cane Tree Corp
 * @email       support@canetree.com
 * @website     https://canetree.com
 */

namespace Joomla\Plugin\System\LoginRemover\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Factory;

final class LoginRemover extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeRender' => 'onBeforeRender',
        ];
    }

    /**
     * Add custom CSS to hide login form elements
     *
     * @return void
     */
    public function onBeforeRender(): void
    {
        $app = Factory::getApplication();

        // Only run on site (frontend)
        if (!$app->isClient('site')) {
            return;
        }

        // Get document
        $document = $app->getDocument();
        
        // Only proceed if we have an HTML document
        if (!method_exists($document, 'addStyleDeclaration')) {
            return;
        }

        $removeForgotPassword = (int) $this->params->get('remove_forgot_password', 1);
        $removeForgotUsername = (int) $this->params->get('remove_forgot_username', 1);
        $removeCreateAccount  = (int) $this->params->get('remove_create_account', 0);

        $css = [];

        // Forgot password
        if ($removeForgotPassword) {
            $css[] = '
                /* Module links */
                ul.mod-login__options a[href*="/component/users/reset"],
                ul.mod-login__options a[href*="view=reset"],
                ul.mod-login__options a[href*="task=user.reset"],
                ul.mod-login__options li > a[href*="/component/users/reset"],
                ul.mod-login__options li > a[href*="view=reset"],
                ul.mod-login__options li > a[href*="task=user.reset"],
                /* com_users login view */
                a.com-users-login__reset[href*="/component/users/reset"],
                a.com-users-login__reset[href*="view=reset"],
                a.com-users-login__reset[href*="task=user.reset"],
                /* Additional selectors */
                .login-links a[href*="reset"],
                .mod-login a[href*="reset"]
                {
                    display: none !important;
                }
            ';
        }

        // Forgot username
        if ($removeForgotUsername) {
            $css[] = '
                /* Module links */
                ul.mod-login__options a[href*="/component/users/remind"],
                ul.mod-login__options a[href*="view=remind"],
                ul.mod-login__options a[href*="task=user.remind"],
                ul.mod-login__options li > a[href*="/component/users/remind"],
                ul.mod-login__options li > a[href*="view=remind"],
                ul.mod-login__options li > a[href*="task=user.remind"],
                /* com_users login view */
                a.com-users-login__remind[href*="/component/users/remind"],
                a.com-users-login__remind[href*="view=remind"],
                a.com-users-login__remind[href*="task=user.remind"],
                /* Additional selectors */
                .login-links a[href*="remind"],
                .mod-login a[href*="remind"]
                {
                    display: none !important;
                }
            ';
        }

        // Create account
        if ($removeCreateAccount) {
            $css[] = '
                /* Module links */
                ul.mod-login__options a[href*="/component/users/registration"],
                ul.mod-login__options a[href*="view=registration"],
                ul.mod-login__options a[href*="task=user.register"],
                ul.mod-login__options li > a[href*="/component/users/registration"],
                ul.mod-login__options li > a[href*="view=registration"],
                ul.mod-login__options li > a[href*="task=user.register"],
                /* com_users login view */
                a[href*="/component/users/registration"].list-group-item,
                a[href*="view=registration"].list-group-item,
                a[href*="task=user.register"].list-group-item,
                /* Additional selectors */
                .login-links a[href*="registration"],
                .mod-login a[href*="registration"]
                {
                    display: none !important;
                }
            ';
        }

        if ($css) {
            $document->addStyleDeclaration(implode("\n", $css));
        }
    }
}
