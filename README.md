darkanakin41/user-bundle
===

This is a bundle that I created on first days of Symfony 4 in order to learn a bit more deeply how to handle 
manually users without using the [FriendsOfSymfony/FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle/)

# Installation
First, you need to add the routes : 
```yaml
darkanakin41_userbundle:
    resource: "@Darkanakin41UserBundle/Controller"
    type: annotation
```

Then update the ```config/packages/framework.yaml``` as follow in order to enable the security
```yaml
framework:
    secret: '%env(APP_SECRET)%'
    csrf_protection: { enabled: true }
```

Then you need to update ```config/packages/security.yaml``` in order to tell Symfony to use my bundle in order to handle 
security for users :
```yaml
security :
    encoders:
        Darkanakin41\UserBundle\Entity\User: bcrypt
    providers:
        darkanakin41_user_provider : 
            id: Darkanakin41\UserBundle\Security\UserProvider
    firewalls:
        main:
            pattern: ^/
            anonymous: true
            simple_form:
                authenticator : Darkanakin41\UserBundle\Security\UsernameAndEmailAuthenticator
                check_path: darkanakin41_user_login
                login_path: darkanakin41_user_login
                csrf_token_generator: security.csrf.token_manager
                default_target_path: front
            logout:
                path: darkanakin41_user_logout
                target: front
``` 

# TODO 
* Create some unit tests and setup a pipeline
* Move to MappedSuperClass for entities
