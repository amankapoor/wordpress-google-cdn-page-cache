[![Build Status](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache.svg?branch=master)](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache)

## Google Cloud CDN Page Cache

This plugin provides a low cost and high performance international page cache solution based on [Google Cloud CDN](https://cloud.google.com/cdn/).

* [Documentation](https://github.com/o10n-x/wordpress-google-cdn-page-cache/tree/master/docs)
* [Description](https://github.com/o10n-x/wordpress-google-cdn-page-cache#description)
* [Version history (Changelog)](https://github.com/o10n-x/wordpress-google-cdn-page-cache/releases)

**This plugin is removed from WordPress.org. Read the story [here](https://github.com/o10n-x/wordpress-css-optimization/issues/4).**

## Installation

![Github Updater](https://github.com/afragen/github-updater/raw/develop/assets/GitHub_Updater_logo_small.png)

This plugin can be installed and updated using [Github Updater](https://github.com/afragen/github-updater) ([installation instructions](https://github.com/afragen/github-updater/wiki/Installation))

## WordPress WPO Collection

This plugin is part of a Website Performance Optimization collection that include [CSS](https://github.com/o10n-x/wordpress-css-optimization), [Javascript](https://github.com/o10n-x/wordpress-javascript-optimization), [HTML](https://github.com/o10n-x/wordpress-html-optimization), [Web Font](https://github.com/o10n-x/wordpress-font-optimization), [HTTP/2](https://github.com/o10n-x/wordpress-http2-optimization), [Progressive Web App (Service Worker)](https://github.com/o10n-x/wordpress-pwa-optimization) and [Security Header](https://github.com/o10n-x/wordpress-security-header-optimization) optimization. 

The WPO optimization plugins provide in all essential tools that enable to achieve perfect [Google Lighthouse Test](https://developers.google.com/web/tools/lighthouse/) scores and to validate a website as [Google PWA](https://developers.google.com/web/progressive-web-apps/), an important ranking factor for Google's [Speed Update](https://searchengineland.com/google-speed-update-page-speed-will-become-ranking-factor-mobile-search-289904) (July 2018).

![Google Lighthouse Perfect Performance Scores](https://github.com/o10n-x/wordpress-css-optimization/blob/master/docs/images/google-lighthouse-pwa-validation.jpg)

The WPO optimization plugins are designed to work together with single plugin performance. The plugins provide the latest optimization technologies and many unique innovations.

### JSON shema configuration

The WPO optimization plugins are based on JSON schema based configuration that enables full control of the optimization using JSON. This provides several great advantages for website performance optimization.

Read more about [JSON schemas](https://github.com/o10n-x/wordpress-o10n-core/tree/master/schemas).

## Google PageSpeed vs Google Lighthouse Scores

While a Google PageSpeed 100 score is still of value, websites with a high Google PageSpeed score may score very bad in Google's new [Lighthouse performance test](https://developers.google.com/web/tools/lighthouse/). 

The following scores are for the same site. It shows that a perfect Google PageSpeed score does not correlate to a high Google Lighthouse performance score.

![Perfect Google PageSpeed 100 Score](https://github.com/o10n-x/wordpress-css-optimization/blob/master/docs/images/google-pagespeed-100.png) ![Google Lighthouse Critical Performance Score](https://github.com/o10n-x/wordpress-css-optimization/blob/master/docs/images/lighthouse-performance-15.png)

### Google PageSpeed score is outdated

For the open web to have a chance of survival in a mobile era it needs to compete with and win from native mobile apps. Google is dependent on the open web for it's advertising revenue. Google therefor seeks a way to secure the open web and the main objective is to rapidly enhance the quality of the open web to meet the standards of native mobile apps.

For SEO it is therefor simple: websites will need to meet the standards set by the [Google Lighthouse Test](https://developers.google.com/web/tools/lighthouse/) (or Google's future new tests). A website with perfect scores will be preferred in search over low performance websites. The officially announced [Google Speed Update](https://searchengineland.com/google-speed-update-page-speed-will-become-ranking-factor-mobile-search-289904) (July 2018) shows that Google is going as far as it can to drive people to enhance the quality to ultra high levels, to meet the quality of, and hopefully beat native mobile apps.

A perfect Google Lighthouse Score includes validation of a website as a [Progressive Web App (PWA)](https://developers.google.com/web/progressive-web-apps/).

Google offers another new website performance test that is much tougher than the Google PageSpeed score. It is based on a AI neural network and it can be accessed on https://testmysite.thinkwithgoogle.com

## Description

**Warning: This plugin requires manual configuration of a [Google Cloud instance](https://cloud.google.com/wordpress/) (a free VPS with SSH) and a custom nginx server**

The plugin uses a simple concept: use Google's [free micro instance](https://cloud.google.com/compute/pricing#freeusage) for a simple custom Nginx based origin pull server and connect that instance with a Google Cloud CDN front as a international page cache layer. The micro Nginx origin pull server could be used for many domains and subdomains enabling to use the free solution for unlimited sites and pay only for [Google Cloud CDN usage costs](https://cloud.google.com/cdn/pricing).

The Nginx server is technically very simple and should perform well on the micro instance.

```nginx
# Origin pull in nginx 
upstream origin_https {
	server	your-server-IP:443;
}
upstream origin_http {
	server	your-server-IP:80;
}

server {
	listen    443 ssl http2;
	server_name yourdomain.com www.yourdomain.com;

	location / {
		proxy_pass   https://origin_https$uri;
		proxy_set_header   host             $host;
		proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
	}
}

server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;

	location / {
		proxy_pass   https://origin_http$uri;
		proxy_set_header   host             $host;
		proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
	}
}

```

**Warning: this is a first prototype Nginx server config. The documentation is under construction.**

The next step is to setup a [Google Cloud Load Balancer](https://cloud.google.com/load-balancing/) with the option `CDN` enabled. This will create a public caching CDN IP that will access your server via the Nginx proxy. You can connect your domain to the CDN IP via your DNS provider (a simple A record) or use Google's international [Cloud DNS](https://cloud.google.com/dns/). 

The CDN will cache content based on HTTP headers which are controlled by the plugin.

The plugin provides in basic management functionality such as controlling the CDN cache expiry. Cache invalidation is not yet possible from PHP but once that's made available by Google it will be added. 

We are interested to learn about your experiences and feedback when using this plugin. Please submit your feedback on the [Github community forum](https://github.com/o10n-x/wordpress-google-cdn-page-cache/issues).