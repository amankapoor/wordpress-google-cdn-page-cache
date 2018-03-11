<?php
namespace O10n;

/**
 * Google CDN Page Cache Settings admin template
 *
 * @package    optimization
 * @subpackage optimization/admin
 * @author     Optimization.Team <info@optimization.team>
 */
if (!defined('ABSPATH') || !defined('O10N_ADMIN')) {
    exit;
}

// print form header
$this->form_start(__('Google CDN Page Cache Settings', 'o10n'), 'googlecdn');

// site host
$host = parse_url(site_url(), PHP_URL_HOST);
$host_www = (strpos($host, 'www.') === 0);

$host_placeholder = $host;
$origin_placeholder = $host;
if (!$host_www) {
    $host_placeholder = str_replace('www.', '', $host);
    if (strpos($host, 'www.') !== 0) {
        $origin_placeholder = 'www.' . $host;
    }
} elseif (strpos($host, 'www.') !== 0) {
    $host_placeholder = 'www.' . $host;
} else {
    $origin_placeholder = str_replace('www.', '', $host);
}
?>

<table class="form-table">
	<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td>
			<label><input type="checkbox" name="o10n[google-cdn.enabled]" data-json-ns="1" value="1"<?php $checked('google-cdn.enabled'); ?> /> Enabled</label>
			<p class="description">Enable the Google CDN Page Cache.</p>
		</td>
	</tr>
	<tr valign="top" data-ns="google-cdn"<?php $visible('google-cdn'); ?>>
		<th scope="row">Public Host</th>
		<td>
			<input type="text" name="o10n[google-cdn.host]" data-json-ns="1" size="60" value="<?php $value('google-cdn.host'); ?>" placeholder="<?php print esc_attr($host_placeholder); ?>" />
			<p class="description">Enter the public host name (accessed by visitors) that is connected to the Google Cloud CDN IP.</p>
		</td>
	</tr>
	<tr valign="top" data-ns="google-cdn"<?php $visible('google-cdn'); ?>>
		<th scope="row">Max Cache Age</th>
		<td>
			<input type="number" name="o10n[google-cdn.max_age]" data-json-ns="1" size="20" value="<?php $value('google-cdn.max_age'); ?>" placeholder="Time in seconds..." />
			<p class="description">Enter the default Google CDN page cache age in seconds. The cache expire time is controlled by <a href="https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching" target="_blank" rel="noopener">HTTP cache headers</a>. The default expire time is 7 days.</p>
			<p class="description">You can manually set the cache expire time for a page using PHP methods in functions.php (<a href="javascript:void(0);" onclick="jQuery('#example_http_cache').toggle();">show example</a>).</p>
			<pre id="example_http_cache" style="display:none;">
if (!is_admin()) {
    O10n\GoogleCDN\set_max_age(86400 * 7); // expire in 7 days

    // alternative
    O10n\GoogleCDN\set_expire(strtotime('<?php print date('Y-m-d H:i', (time() + (86400 * 7))); ?>')); // timestamp

    // alternative
    O10n\GoogleCDN\nocache(); // do not cache
}
			</pre>
		</td>
	</tr>
	<tr valign="top" data-ns="google-cdn"<?php $visible('google-cdn'); ?>>
		<th scope="row" style="padding-top:0px;">&nbsp;</th>
		<td style="padding-top:0px;">
			<h1>Invalidating the Cloud CDN cache</h1>
			<p style="font-size:16px;">Read the <a href="https://cloud.google.com/cdn/docs/cache-invalidation-overview" target="_blank" rel="noopener">documentation</a> for invalidating the cache.</p>
			<p style="font-size:16px;">Google does not yet provide an API that enables automated Google Cloud CDN cache invalidation. Once it becomes available, we will add it to the plugin. Please keep us updated via the <a href="https://github.com/o10n-x/wordpress-google-cdn-page-cache/" target="_blank">Github forum</a>.</p>

			<p class="info_yellow suboption"><strong><span class="dashicons dashicons-lightbulb"></span></strong> To prevent invalidation costs during testing it is possible to use a Windows, Mac or Linux hosts file. <a href="https://encrypted.google.com/search?q=configure+hosts+file+windows+mac" target="_blank" rel="noopener">Search Google</a> for instructions.</p>
		</td>
	</tr>
</table>

<hr />
<?php
    submit_button(__('Save'), 'primary large', 'is_submit', false);

// print form header
$this->form_end();
