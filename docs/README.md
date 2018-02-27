# Google Cloud CDN Page Cache Documentation

### Google Cloud CDN Setup

The setup of Google Cloud CDN is very difficult. It requires advanced settings in Google Cloud Console and the setup of a custom Nginx origin pull server using SSH. The solution provides one of the best international page cache performance possible so it may be worth the investment.

To get started, login to [Google Cloud Console](https://console.cloud.google.com/compute/instances), click the button **Create Instance** and choose the free *micro* instance. You can leave the zone to the default as the Cloud CDN will provide in local access for visitors. Enable both http and https access in the firewall and review the other advanced settings to meet your project's preferences.

Once the instance is created, setup SSH and login to the server. Install Nginx using `sudo apt-get update` and `sudo apt-get install nginx`.