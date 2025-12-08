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
            'onBeforeCompileHead' => 'onBeforeCompileHead',
        ];
    }

    /**
     * Add custom CSS to hide login form elements
     *
     * @return void
     */
    public function onBeforeCompileHead(): void
    {
        $app = Factory::getApplication();

        // Only run on site (frontend)
        if (!$app->isClient('site')) {
            return;
        }

        $removeForgotPassword = (int) $this->params->get('remove_forgot_password', 1);
        $removeForgotUsername = (int) $this->params->get('remove_forgot_username', 1);
        $removeCreateAccount  = (int) $this->params->get('remove_create_account', 0);

        $css = [];

        /**
         * MODULE (SP Page Builder) MARKUP:
         * --------------------------------
         * <ul class="mod-login__options list-unstyled">
         *   <li><a href="/component/users/reset.html">Forgot your password?</a></li>
         *   <li><a href="/component/users/remind.html">Forgot your username?</a></li>
         * </ul>
         *
         * COM_USERS LOGIN PAGE MARKUP:
         * ---------------------------
         * <a class="com-users-login__remind list-group-item"
         *    href="/component/users/remind.html">Forgot your username?</a>
         *
         * Possibly also:
         * <a class="com-users-login__reset list-group-item"
         *    href="/component/users/reset.html">Forgot your password?</a>
         *
         * We target both module and component output.
         */

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
                a.com-users-login__reset[href*="task=user.reset"]
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
                a.com-users-login__remind[href*="task=user.remind"]
                {
                    display: none !important;
                }
            ';
        }

        // Create account (module + possible com_users login links)
        if ($removeCreateAccount) {
            $css[] = '
                /* Module links */
                ul.mod-login__options a[href*="/component/users/registration"],
                ul.mod-login__options a[href*="view=registration"],
                ul.mod-login__options a[href*="task=user.register"],
                ul.mod-login__options li > a[href*="/component/users/registration"],
                ul.mod-login__options li > a[href*="view=registration"],
                ul.mod-login__options li > a[href*="task=user.register"],
                /* com_users login view (common patterns) */
                a[href*="/component/users/registration"].list-group-item,
                a[href*="view=registration"].list-group-item,
                a[href*="task=user.register"].list-group-item
                {
                    display: none !important;
                }
            ';
        }

        if ($css) {
            $document = $app->getDocument();
            $document->addStyleDeclaration(implode("\n", $css));
        }
    }
}