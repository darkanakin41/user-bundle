## INSTALLATION
Pour ajouter les routes, insérez le bloc suivant dans config/routes.yaml

```yaml
plejeune_userbundle:
    resource: "@PLejeuneUserBundle/Controller"
    type: annotation
```

Modifiez le fichier config/packages/framework.yaml comme suit

```yaml
framework:
    secret: '%env(APP_SECRET)%'
    csrf_protection: { enabled: true }
```

Modifiez le fichier config/packages/security.yaml comme suit
```yaml
security :
    encoders:
        PLejeune\UserBundle\Entity\User: bcrypt
    providers:
        plejeune_user_provider : 
            id: PLejeune\UserBundle\Security\UserProvider
    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN
    firewalls:
        main:
            pattern: ^/
            anonymous: true
            simple_form:
                authenticator : PLejeune\UserBundle\Security\UsernameAndEmailAuthenticator
                check_path: plejeune_user_login
                login_path: plejeune_user_login
                csrf_token_generator: security.csrf.token_manager
                default_target_path: front
            logout:
                path: plejeune_user_logout
                target: front
    access_control:
        - { path: '^/', roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: '^/backend', roles: ROLE_ADMIN }
``` 