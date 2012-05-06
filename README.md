MopaRemoteUpdateBundle
======================

This Bundle provides commands to update remote installations directly from your command line.

``` bash
app/console mopa:update:remote yourremote
```


Installation is straight forward:

add it to your composer.json:
``` json
{
    "require": {
        "mopa/remote-update-bundle": "dev-master",
    }
}
```

Include the following bundles in your AppKernel.php:

``` php
// application/ApplicationKernel
public function registerBundles()
{
    return array(
        // ...
        new Mopa\Bundle\RemoteUpdateBundle\MopaRemoteUpdateBundle(),
        new Escape\WSSEAuthenticationBundle\EscapeWSSEAuthenticationBundle(),
        new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),
    	new FOS\RestBundle\FOSRestBundle(),
    	new JMS\SerializerBundle\JMSSerializerBundle($this)
        // ...
    );
}
```

Make sure you do not include bundles twice if you already use them.

Import the necessary configuration in your config.yml: 

``` yaml
imports:
    - { resource: @MopaRemoteUpdateBundle/Resources/config/config.yml }

```

If you do not have a dbal connection in your project also include the sqlite config:

``` yaml
imports:
    - { resource: @MopaRemoteUpdateBundle/Resources/config/config.yml }
    - { resource: @MopaRemoteUpdateBundle/Resources/config/database.yml }

```
If you do not want to have this feature in your productive environment, just include all this in your config_dev.yml just make sure dependencies are set correct, same for AppKernel and Bundles.


Add the Firewall to your security.yml to protect the api from public:

``` yaml
security:
    firewalls:
        wsse_secured:
            pattern:   ^/mopa/update/api/.*
            wsse:
                nonce_dir: null
                lifetime: 300
                provider: in_memory # the user provider providing you user with the role ROLE_REMOTE_UPDATER
```

if you do not have any user providers or no chance to add the ROLE_REMOTE_UPDATER to your user add this too:

``` yaml
security:
    providers:
        in_memory:
            memory:
                users:
                    yourusername:  { password: yoursecretpassword, roles: 'ROLE_REMOTE_UPDATER' }
```



Now setup your remotes in your config.yml:

``` yaml
mopa_remote_update:
    remotes:
        vserverli2: # the alias to use on console
            url: http://www.yoursite.net/ # the url to your side might also be https
            username: test # your username
            password: test # your password
            preUpdate: git pull # optional: a command to run before composer updates the vendors, e.g. update your main application
            postUpdate: app/console schema:update --force # optional: a command to run after composer updates
            updater: live # either live or cron see further down howto deal with cron
        # you can define as many remotes as you like
    composer: /usr/sbin/composer.phar # optional: sets the path to the composer binary if it cant be found
```


Probably you should now update the schema: 

```bash
app/console doctrine:schema:update  --force
```

If you can not use the live updater, e.g. because your webserver does not have permissions to update the vendors, you can create a cronjob on the remote machine to execute the updates:


``` 
*/5   *   *   *  *    /path/to/your/app/console app/console mopa:update:check     #Befehl wird alle 5 Minuten aufgerufen (die Schrittweite wird durch */Schrittweite angegeben).
```


