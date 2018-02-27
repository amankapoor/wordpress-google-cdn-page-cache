[![Build Status](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache.svg?branch=master)](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache)

## Google Cloud CDN Page Cache

This plugin provides a low cost and high performance international page cache solution based on [Google Cloud CDN](https://cloud.google.com/cdn/).

* <a href="https://github.com/o10n-x/wordpress-google-cdn-page-cache/tree/master/docs">Documentation</a>
* <a href="https://wordpress.org/plugins/cf-page-cache/">WordPress Profile</a>
**Warning: This plugin requires manual configuration of a [Google Cloud instance](https://cloud.google.com/wordpress/) (a free VPS with SSH) and a custom nginx server**

The plugin uses a simple concept: use Google's [free micro instance](https://cloud.google.com/compute/pricing#freeusage) for a simple custom Nginx based origin pull server and connect that instance with a Google Cloud CDN front as a international page cache layer. The micro Nginx origin pull server could be used for many domains and subdomains enabling to use the free solution for unlimited sites and pay only for [Google Cloud CDN usage costs](https://cloud.google.com/cdn/pricing).

The plugin provides in basic management functionality such as controlling the CDN cache expiry. Cache invalidation is not yet possible from PHP but once that's made available by Google it will be added. 

We are interested to learn about your experiences and feedback when using this plugin. Please submit your feedback on the [Github community forum](https://github.com/o10n-x/wordpress-google-cdn-page-cache/issues).