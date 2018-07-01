ShapecodeMatomoBundle
============

A Symfony2 Bundle that helps you to use the Matomo Open Analytics Platform with your project.

It contains a Twig function that can insert the tracking code into your website. Plus, you can turn it off with a simple configuration switch so you don't track your dev environment.


Installation
------------
Simply add the following to your composer.json (see http://getcomposer.org/):

```json
"require": {
    "shapecode/matomo-bundle": "~1.0"
}
```

And enable the bundle in `app/AppKernel.php`:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Shapecode\Bundle\MatomoBundle\ShapecodeMatomoBundle(),
    );
}
```

Usage
-----
Somewhere in your views, right before the closing `</body>` tag, insert 

```twig
{{ matomo() }}
```
This will add the appropriate Matomo tracking code as [described in the API reference](http://developer.matomo.org/api-reference/tracking-javascript#where-can-i-find-the-matomo-tracking-code).

You have the ability to change the config on the fly if it necessary

```twig
{{ matomo({
    site_id: 1,
    host_name: 'my.matomo.hostname'
    host_path: '/sub_directory/',
    no_script_tracking: false
}) }}
```

Configuration
-------------
You can configure the bundle in your `config.yml`. Full Example:

```yaml
shapecode_matomo:
    site_id: 1                      # required, no default. site id from matomo tool
    disabled: %kernel.debug%        # not required, default %kernel.debug%. Usually, you only want to include the tracking code in a production environment
    host_name: my.matomo.hostname    # required. no default. Hostname to the matomo instance.
    host_path: "/sub_directory/"    # not required, default null. Path to the tracking script on the host.
    no_script_tracking: true        # not required, default true. Enables Image-Tracking if JavaScript is disabeld.
```

Credits, Copyright and License
------------------------------
Copyright 2017 shapecode. Code released under [the MIT license](LICENSE).

Original script from <http://www.webfactory.de>
