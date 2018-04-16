[![Build Status](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache.svg?branch=master)](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache)

## Google Cloud CDN Page Cache

This plugin provides a low cost and high performance international page cache solution based on [Google Cloud CDN](https://cloud.google.com/cdn/).

* <a href="https://github.com/o10n-x/wordpress-google-cdn-page-cache/tree/master/docs">Documentation</a>
* <a href="https://wordpress.org/plugins/gc-page-cache/">WordPress Profile</a>

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

The WPO optimization plugins are based on JSON schema configuration that enables full control of the optimization using a simple JSON document. This provides several great advantages for website performance optimization.

#### Advantage 1: platform independent

The WPO plugins are not like most other WordPress plugins. The plugins are purely focused on optimization technologies instead of controlling / modifying WordPress. This makes the underlaying optimization technologies platform independent. The same technologies and configuration can be used on Magento, a Microsoft .NET CMS or a Node.js based CMS. 

#### Advantage 2: saving time

The JSON configuration enables much quicker tuning for experts and it enables to quickly copy and paste a proven configuration to a new website.

#### Advantage 3: public optimization knowledge and help

The JSON configuration can be easily shared and discussed on forums, enabling to build public knowledge about the options. Because the optimization configuration is independent from WordPress, the knowledge will be valid for any platform which increases the value, making it more likely to be able to receive free help.

#### Advantage 4: a basis for Artificial Intelligence

The JSON configuration concept, when completed, enables fine grained tuning of the optimization, not just on a per page level but even per individual visitor or based on the environment. This will enable to optimize the performance based on the [save-data](https://developers.google.com/web/updates/2016/02/save-data) header or for example to change an individual CSS optimization setting specifically for iPhone 5 devices. 

While the JSON shema concept makes it more easy for human editors to perform such complex environment based optimization, it also provides a basis for a future AI to take full control over the optimization, enabling to achieve the absolute best website performance result for each individual user automatically.

While the AI may one day supplement or take over, experts will have a clear view of what the AI is doing (it produces simple JSON that is used by humans) and will be able to override at any point.

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