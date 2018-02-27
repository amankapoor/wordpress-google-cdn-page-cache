<?php
namespace O10n;

/**
 * Global Google Cloud CDN Page Cache Admin Controller
 *
 * @package    optimization
 * @subpackage optimization/controllers/admin
 * @author     Optimization.Team <info@optimization.team>
 */
if (!defined('ABSPATH')) {
    exit;
}

class AdminGlobalgooglecdn extends ModuleAdminController implements Module_Admin_Controller_Interface
{

    /**
     * Load controller
     *
     * @param  Core       $Core Core controller instance.
     * @return Controller Controller instance.
     */
    public static function &load(Core $Core)
    {
        // instantiate controller
        return parent::construct($Core, array(
            'options'
        ));
    }

    /**
     * Setup controller
     */
    protected function setup()
    {

        // add admin bar menu
        add_action('admin_bar_menu', array( $this, 'admin_bar'), 100);
    }

    /**
     * Admin bar option
     *
     * @param  object       Admin bar object
     */
    final public function admin_bar($admin_bar)
    {
        // current url
        if (is_admin()
            || (defined('DOING_AJAX') && DOING_AJAX)
            || in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))
        ) {
            $currenturl = home_url();
        } else {
            $currenturl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        // WPO plugin or more than 1 optimization module, add to optimization menu
        if (defined('O10N_WPO_VERSION') || count($this->core->modules()) > 1) {
            $admin_bar->add_node(array(
                'parent' => 'o10n',
                'id' => 'o10n-google-cdn',
                'title' => '<img src="' . $this->core->modules('googlecdn')->dir_url() . 'admin/images/google-cloud.svg" style="width:16px;height:16px;margin-right:.2em;" align="absmiddle" alt="Google CDN" style="margin-right:2px;"> ' . __('Page Cache', 'o10n') . '',
                'href' => add_query_arg(array( 'page' => 'o10n-googlecdn' ), admin_url('admin.php'))
            ));

            $admin_base = 'admin.php';
        } else {
            $admin_bar->add_menu(array(
                'id' => 'o10n-google-cdn',
                'title' => '<span class="ab-label"><img src="' . $this->core->modules('googlecdn')->dir_url() . 'admin/images/google-cloud.svg" style="width:16px;height:16px;margin-top:-2px;" align="absmiddle" alt="Google CDN"></span>',
                'href' => add_query_arg(array( 'page' => 'o10n-googlecdn' ), admin_url('options-general.php')),
                'meta' => array( 'title' => __('Google CDN Page Cache', 'o10n'), 'class' => 'ab-sub-secondary' )
            ));

            $admin_base = 'options-general.php';
        }

        // use public host
        $currenturl_host = $this->options->get('google-cdn.host');
        if (!$currenturl_host) {
            // parse url
            $currenturl_host = parse_url($currenturl, PHP_URL_HOST);
        } else {
            $currenturl = str_replace($this->options->get('google-cdn.origin'), $currenturl_host, $currenturl);
        }

        $settings_url = add_query_arg(array( 'page' => 'o10n-googlecdn' ), admin_url($admin_base));

        if (!defined('O10N_WPO_VERSION') || count($this->core->modules()) <= 1) {

            // title header
            $admin_bar->add_group(array(
                'parent' => 'o10n-google-cdn',
                'id' => 'o10n-google-cdn-top'
            ));
            $admin_bar->add_node(array(
                'parent' => 'o10n-google-cdn-top',
                'id' => 'o10n-google-cdn-title',
                'title' => '<span style="font-weight:bold;">' . __('Google CDN Page Cache', 'o10n') . '</span>',
                'meta' => array( 'title' => __('Clear Google CDN cache for current page.', 'o10n') )
            ));
        }

        if ($this->options->bool('google-cdn.invalidation.enabled')) {
            
            // cache invalidation group
            $admin_bar->add_group(array(
                'parent' => 'o10n-google-cdn',
                'id' => 'o10n-google-cdn-second',
                'meta' => array(
                    'class' => 'ab-sub-secondary',
                )
            ));

            if (!is_admin()) {

                // path to invalidate
                $path = preg_replace('#http(s):\/\/[^\/]+(/|$)#Ui', '/', $currenturl);

                $admin_bar->add_node(array(
                    'parent' => 'o10n-google-cdn-second',
                    'id' => 'o10n-google-cdn-clear-page',
                    'title' => __('Invalidate Page', 'o10n'),
                    'href' => add_query_arg(array( 'page' => 'o10n-googlecdn', 'tab' => 'invalidation', 'purge' => 'page', 'path' => $path, 'return' => $currenturl, 't' => time() ), admin_url($admin_base)),
                    'meta' => array( 'title' => __('Clear Google CDN cache for current page.', 'o10n'), 'onclick' => 'return o10n_google_cdn_confirm_purge(this);' )
                ));
            }

            $admin_bar->add_node(array(
                'parent' => 'o10n-google-cdn-second',
                'id' => 'o10n-google-cdn-clear',
                'title' => __('Invalidate All /*', 'o10n'),
                'href' => add_query_arg(array( 'page' => 'o10n-googlecdn', 'tab' => 'invalidation', 'purge' => 'cf', 'return' => (is_admin()) ? false : $currenturl, 't' => time() ), admin_url($admin_base)),
                'meta' => array( 'title' => __('Clear Google CDN cache for all pages.', 'o10n'), 'onclick' => 'return o10n_google_cdn_confirm_purge(this);' )
            ));

            $admin_bar->add_node(array(
                'parent' => 'o10n-google-cdn-second',
                'id' => 'o10n-google-cdn-clear-plugins',
                'title' => __('Purge Plugin Caches', 'o10n'),
                'href' => add_query_arg(array( 'page' => 'o10n-googlecdn', 'tab' => 'invalidation', 'purge' => 'plugins', 'return' => (is_admin()) ? false : $currenturl, 't' => time() ), admin_url($admin_base)),
                'meta' => array( 'title' => __('Clear the cache of page cache related plugins such as Autoptimize, WP Super Cache and others.', 'o10n'), 'onclick' => 'return o10n_google_cdn_confirm_purge(this);' )
            ));

            $admin_bar->add_node(array(
                'parent' => 'o10n-google-cdn-second',
                'id' => 'o10n-google-cdn-clear-all',
                'title' => __('Invalidate All + Plugin Caches', 'o10n'),
                'href' => add_query_arg(array( 'page' => 'o10n-googlecdn', 'tab' => 'invalidation', 'purge' => 'all', 'return' => (is_admin()) ? false : $currenturl, 't' => time() ), admin_url($admin_base)),
                'meta' => array( 'title' => __('Invalidate all pages on Google CDN (/*) + clear the cache of plugins such as Autoptimize, WP Super Cache and others.', 'o10n'), 'onclick' => 'return o10n_google_cdn_confirm_purge(this);' )
            ));
        }

        // support
        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn',
            'id' => 'o10n-google-cdn-support',
            'title' => '<span class="dashicons dashicons-phone o10n-menu-icon"></span> ' . __('Google Cloud Support', 'o10n'),
            'href' => 'https://cloud.google.com/support/',
            'meta' => array( 'title' => __('Google Cloud Support', 'o10n'), 'target' => '_blank' )
        ));

        // console
        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn',
            'id' => 'o10n-google-cdn-console',
            'title' => '<span class="dashicons dashicons-admin-generic o10n-menu-icon"></span> ' . __('Google Cloud Console', 'o10n'),
            'href' => 'https://console.cloud.google.com/compute/instances',
            'meta' => array( 'title' => __('Google Cloud Console', 'o10n'), 'target' => '_blank' )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn',
            'id' => 'o10n-google-cdn-speed-tests',
            'title' => '<span class="dashicons dashicons-dashboard o10n-menu-icon"></span> ' . __('Speed Tests', 'o10n'),
            'href' => false,
            'meta' => array( 'title' => __('Speed Tests', 'o10n') )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn-speed-tests',
            'id' => 'o10n-google-cdn-securi-speed-test',
            'title' => __('Securi', 'o10n'),
            'href' => 'https://performance.sucuri.net/domain/' . $currenturl_host . '?utm_source=wordpress&utm_medium=plugin&utm_term=optimization&utm_campaign=Google CDN%20Page%20Cache',
            'meta' => array( 'title' => __('Securi Speed Test', 'o10n'), 'target' => '_blank' )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn-speed-tests',
            'id' => 'o10n-google-cdn-keycdn-speed-test',
            'title' => __('KeyCDN', 'o10n'),
            'href' => 'https://tools.keycdn.com/speed?url='.urlencode($currenturl).'&utm_source=wordpress&utm_medium=plugin&utm_term=optimization&utm_campaign=Google CDN%20Page%20Cache',
            'meta' => array( 'title' => __('KeyCDN Speed Test', 'o10n'), 'target' => '_blank' )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn-speed-tests',
            'id' => 'o10n-google-cdn-uptrends-speed-test',
            'title' => __('Uptrends', 'o10n'),
            'href' => 'https://www.uptrends.com/tools/website-speed-test?url='.urlencode($currenturl).'&utm_source=wordpress&utm_medium=plugin&utm_term=optimization&utm_campaign=Google CDN%20Page%20Cache',
            'meta' => array( 'title' => __('Uptrends Speed Test', 'o10n'), 'target' => '_blank' )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn-speed-tests',
            'id' => 'o10n-google-cdn-dotcom-speed-test',
            'title' => __('Dotcom-Tools.com', 'o10n'),
            'href' => 'https://www.dotcom-tools.com/website-speed-test.aspx?url='.urlencode($currenturl).'&utm_source=wordpress&utm_medium=plugin&utm_term=optimization&utm_campaign=Google CDN%20Page%20Cache',
            'meta' => array( 'title' => __('Dotcom-Tools Speed Test', 'o10n'), 'target' => '_blank' )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn-speed-tests',
            'id' => 'o10n-google-cdn-webpagetest',
            'title' => __('WebPageTest.org', 'o10n'),
            'href' => 'https://www.webpagetest.org/?url='.urlencode($currenturl).'&utm_source=wordpress&utm_medium=plugin&utm_term=optimization&utm_campaign=Google CDN%20Page%20Cache',
            'meta' => array( 'title' => __('WebPageTest.org Speed Test', 'o10n'), 'target' => '_blank' )
        ));

        $admin_bar->add_node(array(
            'parent' => 'o10n-google-cdn-speed-tests',
            'id' => 'o10n-google-cdn-gtmetrix',
            'title' => __('GTMetrix', 'o10n'),
            'href' => 'https://gtmetrix.com/?url='.urlencode($currenturl).'&utm_source=wordpress&utm_medium=plugin&utm_term=optimization&utm_campaign=Google CDN%20Page%20Cache',
            'meta' => array( 'title' => __('GTMetrix Speed Test', 'o10n'), 'target' => '_blank' )
        ));
    }
}
