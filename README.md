# vBridgeCloud/C-all App Login Bundle

Use this bundle to authenticate a user in a Symfony application through C-all's login app.

## Installation

Require the bundle with composer
```
composer require vbridgecloud/c-all-app-login-bundle
```
Add it to your Symfony app `bundles.php`
```php
# config/bundles.php
<?php

return [
    // ...
    vBridgeCloud\CallLoginBundle\CallLoginBundle::class => ['all' => true],
];
```
Configure it
```yaml
# config/packages/vbridgecloud_calllogin.yaml
call_login:
  public_url: '%env(LOGIN_PUBLIC_URL)%' # The public URL the login app is available through
  internal_url: '%env(LOGIN_INTERNAL_URL)%' # Internal DNS url for the login app
  client_id: '%env(LOGIN_CLIENT_ID)%' # Client ID to be used to authenticate
  client_secret: '%env(LOGIN_CLIENT_SECRET)%' # Client secret
```
Configure your security:
```yaml
# config/packages/security.yaml
security:
    providers:
        user_provider:
            id: call_login.user_provider
    firewalls:
        # ...
        main:
            custom_authenticators:
                - call_login.authenticator
            entry_point: call_login.entrypoint
        # ...

    access_control:
        - { path: ^/login/authorize, roles: IS_AUTHENTICATED_ANONYMOUSLY }
```
Whatever authorize endpoint you end up using (see below), it must be

### Authorization endpoint
This bundle needs an endpoint to be redirected back to, either use the provided controller and route :
```yaml
# config/routes.yaml
call_login:
  resource: '@CallLoginBundle/Resources/config/routing.yaml'
```

Or add your own route in a controller somewhere and configure it:
```yaml
# config/packages/vbridgecloud_calllogin.yaml
call_login:
    oauth_redirect_path: 'my_authorize_route'
```

### Optional configuration
By default, after login, your app will be redirected to the `home` route. Make sure you either have a named route `home` or override it through configuration with the `login_redirect_path` config option.
