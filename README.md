# PhpList CAS/LDAP plugin
PhpList plugin to user CAS SSO to authenticate administrators and retrieve their informations and superuser rights from LDAP.

## Installation ##

### Requirements ###
This plugin need [PhpCas](https://github.com/Jasig/phpCAS) and [Net_LDAP2](http://pear.php.net/package/Net_LDAP2) libraries to work. On **Debian GNU/Linux**, you could install it using packages **php-cas** and **php-net-ldap2**.

After installation, you have to configure librairies path.

### Set the plugin directory ###
The default plugin directory is `plugins` within the admin directory.

You can use a directory outside of the web root by changing the definition of `PLUGIN_ROOTDIR` in config.php.
The benefit of this is that plugins will not be affected when you upgrade phplist.

### Install through phplist ###
Install on the Plugins page (menu Config > Plugins) using the package URL `https://github.com/brenard/phplist-casldap-plugin/archive/master.zip`.

### Install manually ###
Download the plugin zip file from <https://github.com/brenard/phplist-casldap-plugin/archive/master.zip>

Put `CASLdapPlugin.php` content in the zip file in your phplist plugins directory.

### Enable and configure the plugin ###
Enable it on the Plugins page (menu Config > Plugins). After, navigate to the phplist settings page and fill in your CAS/LDAP informations.
It is under the CAS/LDAP section.
