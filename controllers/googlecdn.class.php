<?php
namespace O10n;

/**
 * Google Cloud CDN Page Cache Controller
 *
 * @package    optimization
 * @subpackage optimization/controllers
 * @author     Optimization.Team <info@optimization.team>
 */
if (!defined('ABSPATH')) {
    exit;
}

class Googlecdn extends Controller implements Controller_Interface
{
    private $http_host;
    private $public_host;

    private $google_cdn_pull = false; // Origin pull request from CDN?

    private $default_max_age = 604800; // maintain cache for 7 days by default
    private $expire_date; // Google CDN cache expire date (HTTP headers)
    private $expire_age;

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
            'options',
            'env'
        ));
    }

    /**
     * Setup controller
     */
    protected function setup()
    {
        // verify if page cache is enabled
        if (!$this->options->bool('google-cdn.enabled')) {
            return;
        }

        /*
        // configure Google CDN hosts
        $this->public_host = $this->options->get('google-cdn.host');
        if (empty($this->public_host)) {
            return;
        }*/

        $protocol = ($this->env->is_ssl()) ? 'https' : 'http';

        // Google Cloud CDN origin pull request
        if (isset($_SERVER['HTTP_X_GOOGLE_PAGE_CACHE'])) {
            $this->google_cdn_pull = true;
        }
 
        // enable filter on public / origin host
        add_action('init', array($this, 'wp_init'), $this->first_priority);
        add_action('admin_init', array($this, 'wp_init'), $this->first_priority);

        // add HTTP cache headers
        add_action('send_headers', array($this, 'add_cache_headers'), $this->first_priority);
    }
    
    /**
     * WordPress init hook
     */
    final public function wp_init()
    {
        //$this->public_host = apply_filters('o10n_google_cdn_public_host', $this->public_host);
    }

    /**
     * WordPress HTTP headers hook
     */
    final public function add_cache_headers()
    {
        // disable for admin and login page
        if (is_admin() || $GLOBALS['pagenow'] === 'wp-login.php') {
            return;
        }

        $default_max_age = $this->options->get('google-cdn.max_age', false);
        if (!$default_max_age || !is_numeric($default_max_age)) {
            $default_max_age = $this->default_max_age;
        }
        $time = time();

        // set expire date based on default cache age
        if (!$this->expire_date || !is_numeric($this->expire_date)) {
            $this->expire_date = ($time + $default_max_age);
        }

        // max age header
        if ($this->expire_age) {
            $age = $this->expire_age;
            $date = (time() + $age);
        } else {
            $age = ($this->expire_date - $time);
            $date = $this->expire_date;
        }

        // no cache headers
        if ($age < 0) {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        } else {

            // max age header
            header("Cache-Control: public, must-revalidate, max-age=" . $age);
        }

        // expire header
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', $date));
    }

    /**
     * Set Google CDN cache age
     */
    final public function set_max_age($age)
    {
        // verify
        if (!$age || !is_numeric($age)) {
            throw new Exception('Age not numeric in O10n\GoogleCDN::set_max_age()', false, array('persist' => 'expire','max-views' => 3));
        }

        // set expire age
        $this->expire_age = $age;

        // set expire date
        $this->expire_date = (time() + $age);
    }

    /**
     * Set Google CDN cache expire date
     */
    final public function set_expire($timestamp)
    {

        // try to convert string date
        if ($timestamp && !is_numeric($timestamp)) {
            try {
                $timestamp = strtotime($timestamp);
            } catch (\Exception $err) {
                $timestamp = false;
            }
        }

        // verify
        if (!$timestamp) {
            throw new Exception('Invalid timestamp in O10n\GoogleCDN::set_expire()', false, array('persist' => 'expire','max-views' => 3));
        }

        // set expire date
        $this->expire_date = $timestamp;
        $this->expire_age = ($timestamp - time());
    }
}
