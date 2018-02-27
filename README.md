[![Build Status](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache.svg?branch=master)](https://travis-ci.org/o10n-x/wordpress-google-cdn-page-cache)

## Google Cloud CDN Page Cache

This plugin provides a low cost and high performance international page cache solution based on [Google Cloud CDN](https://cloud.google.com/cdn/).

* <a href="https://github.com/o10n-x/wordpress-google-cdn-page-cache/tree/master/docs">Documentation</a>
* <a href="https://wordpress.org/plugins/gc-page-cache/">WordPress Profile</a>

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
	server_name o10n-google-cdn.pagespeed.pro;

	location / {
		proxy_pass							https://origin_https$uri;
		proxy_set_header  host 				$host;
		proxy_set_header  X-Forwarded-For 	$proxy_add_x_forwarded_for;
	}
}

server {
    listen 80;
    server_name o10n-google-cdn.pagespeed.pro;

    location / {
		proxy_pass							http://origin_http$uri;
		proxy_set_header  host 				$host;
		proxy_set_header  X-Forwarded-For 	$proxy_add_x_forwarded_for;
    }
}

```

** Warning: this is a first prototype Nginx server config. The documentation is under construction. **

The plugin provides in basic management functionality such as controlling the CDN cache expiry. Cache invalidation is not yet possible from PHP but once that's made available by Google it will be added. 

We are interested to learn about your experiences and feedback when using this plugin. Please submit your feedback on the [Github community forum](https://github.com/o10n-x/wordpress-google-cdn-page-cache/issues).