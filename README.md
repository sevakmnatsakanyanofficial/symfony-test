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

3. Detect postgres container IP (it needs to connect from php fpm container):
```
docker inspect \
    -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' symfony-api-pgsql
```
and add IP in .env.local file with format:
```
DATABASE_URL="postgresql://root:root@!IP!:5432/symfonyapi?charset=utf8"
```

4. RBAC table migration
```
php yii migrate --migrationPath=@yii/rbac/migrations
```

5. Other tables migrations
```
php yii migrate
```

6. Run fixtures
```
bin/console doctrine:fixtures:load
```

ENJOY PROJECT!!!
