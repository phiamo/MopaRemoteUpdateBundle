MopaRemoteUpdateBundle
======================

This Bundle provides commands to update remote installations directly from your command line.

``` bash
app/console mopa:update:remote yourremote
```
## Installation

Installation is quite easy


### Step 1: Installation using your composer.json:

``` json
{
    "require": {
        "mopa/remote-update-bundle": "dev-master",
    }
}
```

### Step 2: Include the following bundles in your AppKernel.php:

Make sure you do not include bundles twice if you already use them.

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

### Step 2: Add the necessary routing information to your routing.yml

``` yaml
mopa_remote_update_bundle:
    type:     rest
    resource: "@MopaRemoteUpdateBundle/Resources/config/routing.yml"
```

### Step 3: Add the necessary firewall configurations to your security.yml

To protect the api from public we need a firewall and a user provider:

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
                    '%yourparameteruser%':  { password: '%yourparameterpassword%', roles: 'ROLE_REMOTE_UPDATER' }
```

And in your parameters.yml:

``` yaml
parameters:
    yourparameteruser: someusername
    yourparameterpassword: somesecretpassword
```


### Step 4: Add the necessary configuration to your config.yml

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


Setup your remotes in your config.yml:

``` yaml
mopa_remote_update:
    remotes:
        my_remote: # the alias to use on console, you can define as many remotes as you like
            url: http://www.yoursite.net/ # the url to your side might also be https
            username: test # your username
            password: test # your password
            preUpdate: git pull # optional: a command to run before composer updates the vendors, e.g. update your main application
            postUpdate: bin/post-composer.sh -w # optional: a command to run after composer updates
            updater: live # either live or cron see further down howto deal with cron
    composer: /usr/sbin/composer.phar # optional: sets the path to the composer binary if it cant be found
```

there is a usefule postUpdate packaged which has several features: 

```
bin/post-composer.sh -h
```

To get a description

The bin/envvars.default has to be copied to bin/envvars and editied, to make app/cache and app/logs wirtable for webserver.

### Step 5: update/create the schema: 

```bash
app/console doctrine:schema:update  --force
```

### Step 6(Optional): configure cron updater:

If you can not use the live updater, e.g. because your webserver does not have permissions to update the vendors, you can create a cronjob on the remote machine to execute the updates:


``` 
*/5   *   *   *  *    /path/to/your/app/console mopa:update:check # checks every 5 minutes if there is a new update job in queue
```

### Step 7: commit your changes to your favorite vcs and setup your remote.

Now its time to push the same to your server and make sure everything is working as expected.
You should also check the postUpdate command an composer are found.

To test the update you can use 

```bash
app/console mopa:update:local my_remote
```

### Step 8(Optional): ignore MopaRemoteUpdateBundle bin files

Optionally add this to your vcs ignore file (e.g. .gitignore):

```
# ignore MopaRemoteUpdateBundle bin files
bin/post-composer.sh
bin/envvars.default
```

