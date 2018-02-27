<?php
namespace O10n\GoogleCDN;

/**
 * Global functions
 *
 * @package    optimization
 * @subpackage optimization/controllers
 * @author     Optimization.Team <info@optimization.team>
 */

// set Google Cloud CDN cache age for page
function set_max_age($age)
{
    \O10n\Core::get('googlecdn')->set_max_age($age);
}

// set Google Cloud CDN cache expire date
function set_expire($timestamp)
{
    \O10n\Core::get('googlecdn')->set_expire($timestamp);
}

// set Google Cloud CDN no cache
function nocache()
{
    \O10n\Core::get('googlecdn')->set_age(-1);
}
