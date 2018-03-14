<?php
namespace O10n;

/**
 * Google Cloud CDN Page Cache
 *
 * Low cost and high performance international page cache based on Google Cloud CDN.
 *
 * @link              https://github.com/o10n-x/
 * @package           o10n
 *
 * @wordpress-plugin
 * Plugin Name:       Google Cloud Page Cache CDN
 * Description:       Low cost and high performance international page cache based on Google Cloud CDN.
 * Version:           1.0.15
 * Author:            Optimization.Team
 * Author URI:        https://optimization.team/
 * Text Domain:       o10n
 * Domain Path:       /languages
 */

if (! defined('WPINC')) {
    die;
}

// abort loading during upgrades
if (defined('WP_INSTALLING') && WP_INSTALLING) {
    return;
}

// settings
$module_version = '1.0.15';
$minimum_core_version = '0.0.20';
$plugin_path = dirname(__FILE__);

// load the optimization module loader
if (!class_exists('\O10n\Module')) {
    require $plugin_path . '/core/controllers/module.php';
}

// load module
new Module(
    'googlecdn',
    'Google CDN Page Cache',
    $module_version,
    $minimum_core_version,
    array(
        'core' => array(
            'googlecdn'
        ),
        'admin' => array(
            'AdminGooglecdn'
        ),
        'admin_global' => array(
            'AdminGlobalgooglecdn'
        )
    ),
    false,
    array(),
    __FILE__
);

// load public functions in global scope
require $plugin_path . '/includes/global.inc.php';
