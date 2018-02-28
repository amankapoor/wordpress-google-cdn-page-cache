<?php
namespace O10n;

/**
 * Google Cloud CDN Page Cache Admin Controller
 *
 * @package    optimization
 * @subpackage optimization/controllers/admin
 * @author     Optimization.Team <info@optimization.team>
 */
if (!defined('ABSPATH')) {
    exit;
}

class AdminGooglecdn extends ModuleAdminController implements Module_Admin_Controller_Interface
{
    protected $admin_base = 'options-general.php';

    // tab menu
    protected $tabs = array(
        'intro' => array(
            'title' => '<span class="dashicons dashicons-admin-home"></span>',
            'title_attr' => 'Intro'
        ),
        'settings' => array(
            'title' => 'Settings'
        )
    );

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
            'AdminView',
            'options'
        ));
    }

    /**
     * Setup controller
     */
    protected function setup()
    {
        // settings link on plugin index
        add_filter('plugin_action_links_' . $this->core->modules('googlecdn')->basename(), array($this, 'settings_link'));

        // meta links on plugin index
        add_filter('plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2);

        // title on plugin index
        add_action('pre_current_active_plugins', array( $this, 'plugin_title'), 10);

        // admin options page
        add_action('admin_menu', array($this, 'admin_menu'), 50);

        // reorder menu
        add_filter('custom_menu_order', array($this, 'reorder_menu'), 100);
      
        // enqueue scripts
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), $this->first_priority);
    }

    /**
     * Enqueue scripts and styles
     */
    final public function enqueue_scripts()
    {
        // skip if user is not logged in
        if (!is_admin() || !is_user_logged_in()) {
            return;
        }

        $module_url = $this->core->modules('googlecdn')->dir_url();
        $version = $this->core->modules('googlecdn')->version();

        // preload menu icon?>
<link rel="preload" href="<?php print $module_url; ?>admin/images/google-cloud.svg" as="image" type="image/svg+xml" />
<?php
    }

    /**
     * Admin menu option
     */
    public function admin_menu()
    {
        global $submenu;

        // WPO plugin or more than 1 optimization module, add to optimization menu
        if (defined('O10N_WPO_VERSION') || count($this->core->modules()) > 1) {
            add_submenu_page('o10n', __('Google CDN Page Cache', 'o10n'), __('Google CDN', 'o10n'), 'manage_options', 'o10n-googlecdn', array(
                 &$this->AdminView,
                 'display'
             ));

            // change base to admin.php
            $this->admin_base = 'admin.php';
        } else {

            // add menu entry
            add_submenu_page('options-general.php', __('Google CDN Page Cache', 'o10n'), __('Google CDN', 'o10n'), 'manage_options', 'o10n-googlecdn', array(
                 &$this->AdminView,
                 'display'
             ));
        }
    }

    /**
     * Settings link on plugin overview.
     *
     * @param  array $links Plugin settings links.
     * @return array Modified plugin settings links.
     */
    final public function settings_link($links)
    {
        $settings_link = '<a href="'.esc_url(add_query_arg(array('page' => 'o10n-googlecdn','tab' => 'settings'), admin_url($this->admin_base))).'">'.__('Settings').'</a>';
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * Show row meta on the plugin screen.
     */
    public static function plugin_row_meta($links, $file)
    {
        if ($file == $this->core->modules('googlecdn')->basename()) {
            $lgcode = strtolower(get_locale());
            if (strpos($lgcode, '_') !== false) {
                $lgparts = explode('_', $lgcode);
                $lgcode = $lgparts[0];
            }
            if ($lgcode === 'en') {
                $lgcode = '';
            }

            $row_meta = array(
                'o10n_google_cloud_console' => '<a href="' . esc_url('https://console.cloud.google.com/compute/instances') . '" target="_blank" title="' . esc_attr(__('Google Cloud Console', 'o10n')) . '">' . __('Google Cloud Console', 'o10n') . '</a>',

                'o10n_google_cloud_support' => '<a href="' . esc_url('https://cloud.google.com/support/') . '" target="_blank" title="' . esc_attr(__('Google Cloud Support', 'o10n')) . '" style="font-weight:bold;color:#E47911;">' . __('Google Cloud Support', 'o10n') . '</a>'
            );

            return array_merge($links, $row_meta);
        }

        return (array) $links;
    }

    /**
     * Plugin title modification
     */
    public function plugin_title()
    {
        ?><script>jQuery(function($){var r=$('*[data-plugin="<?php print $this->core->modules('googlecdn')->basename(); ?>"]');
            $('.plugin-title strong',r).html('<?php print $this->core->modules('googlecdn')->name(); ?><a href="https://optimization.team" class="g100" style="font-size: 10px;float: right;font-weight: normal;opacity: .2;line-height: 14px;">O10N</span>');
});</script><?php
    }

    /**
     * Reorder menu
     */
    public function reorder_menu($menu_order)
    {
        global $submenu;

        // move to end of list
        if (defined('O10N_WPO_VERSION') || count($this->core->modules()) > 1) {
            $menukey = 'o10n';
        } else {
            $menukey = 'options-general.php';
        }
        foreach ($submenu[$menukey] as $key => $item) {
            if ($item[2] === 'o10n-google-cdn') {
                $submenu[$menukey][] = $item;
                unset($submenu[$menukey][$key]);
            }
        }
    }
}
