# Google Cloud CDN Page Cache Documentation

The plugin uses a simple concept: use Google's [free micro instance](https://cloud.google.com/compute/pricing#freeusage) for a simple custom Nginx based origin pull server and connect that instance with a Google Cloud CDN front as a international page cache layer. The micro Nginx origin pull server could be used for many domains and subdomains enabling to use the free solution for unlimited sites and pay only for [Google Cloud CDN usage costs](https://cloud.google.com/cdn/pricing).

### Google Cloud CDN Setup

The setup of Google Cloud CDN is difficult. It requires advanced settings in Google Cloud Console and the setup of a custom Nginx origin pull server using SSH. The solution provides one of the best international page cache performance possible so it may be worth the investment.

To get started, login to [Google Cloud Console](https://console.cloud.google.com/compute/instances), click the button **Create Instance** and choose the free *micro* instance. You can leave the zone to the default as the Cloud CDN will provide in local access for visitors. Enable both http and https access in the firewall and review the other advanced settings to meet your project's preferences.

Once the instance is created, setup SSH and login to the server. Install Nginx using `sudo apt-get update` and `sudo apt-get install nginx`.

### Nginx Origin Pull Server

The Nginx server is technically very simple and should perform well on the micro instance. The nginx server configuration files are located in `/etc/nginx/sites-enabled/`. You can manage individual files for domains or add a single `origin-pull.conf`.

**Warning: the following Nginx origin pull server config is a first prototype. The documentation is under construction. Nginx expert? Please submit your advise on the forum.**

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
	server_name yourseconddomain.com www.yourseconddomain.com;

	location / {
		proxy_pass   https://origin_https$uri;
		proxy_set_header   host             $host;
		proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
	}
}

server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    server_name yourseconddomain.com www.yourseconddomain.com;

	location / {
		proxy_pass   https://origin_http$uri;
		proxy_set_header   host             $host;
		proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
	}
}

```

The next step is to setup a [Google Cloud Load Balancer](https://cloud.google.com/load-balancing/) with the option `CDN` enabled. This will create a public caching CDN IP that will access your server via the Nginx proxy. You can connect your domain to the CDN IP via your DNS provider (a simple A record) or use Google's international [Cloud DNS](https://cloud.google.com/dns/). 

The setup of the CDN should be very simple. Google reported the following about it:

> As I mentioned in my previous response, enabling the Cloud CDN in GCP is very simple. You just have to follow the guide [here](https://cloud.google.com/cdn/docs/quickstart#enable_product_name_short_for_a_pre-existing_backend_service). 

The CDN will now cache content based on HTTP headers which are controlled by the plugin.