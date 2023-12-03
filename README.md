DEVELOPMENT ENVIRONMENT
-----------------------
We use Docker for 'dev' development. And to deploy project on local machine you need follow steps:

1 . Run containers:
```
docker-compose up -d
```

2 . Add to /etc/hosts:
```
127.0.0.1    symfonyapi.loc
127.0.0.1    www.symfonyapi.loc
```
instead of 127.0.0.1 may be other address (check your docker setup)

3 . php-fpm container:

1. Log in bash
```
docker exec -it symfony-api-php-fpm bash
```
and you can use bash to control application.

2. Run composer in php-fpm container:
```
php composer install
```

3. Init app in php-fpm container:
```
php init --env=Development --overwrite=All
```

4. RBAC table migration
```
php yii migrate --migrationPath=@yii/rbac/migrations
```

5. Other tables migrations
```
php yii migrate
```

ENJOY PROJECT!!!