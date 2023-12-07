Тестовое задание по задаче <a href="https://github.com/systemeio/test-for-candidates/"> https://github.com/systemeio/test-for-candidates/ </a>
-----------------------
Приветствую
-----------------------
Спасибо, что уделяете время проверке моей работы. Поэтому добавлю некоторые заметки.

Я работаю с symfony впервые, поэтому могут быть неточности в конфигах или
неоптимальные решения с точки зрения пользования возможными инструментами фреймворка.
Для более глубокого понимания фреймворка, очевидно, потребуется больше практики.

Поэтому в решении больше сконцентрировался в подходах вне зависимости от фреймворка.
Также отказался от неуместного усложнения в рамках конкретной задачи.
Конечно, можно дробить все на еще более мелкие части, но на первом месте
требование к задаче и, если оно решается проще, то не имеет смысла усложнять.
В том числе в тестах не покрыты все возможные исходы, а всего несколько,
что достаточно для демонстрации умения работать с инструментом.

Далее описываю как развернуть проект локально и проверить.

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

3 . Detect postgres container IP (it needs to connect from php fpm container):
```
docker inspect \
    -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' symfony-api-pgsql
```
and add IP in .env.local file with format:
```
DATABASE_URL="postgresql://root:root@!IP!:5432/symfonyapi?charset=utf8"
```

4 . php-fpm container:

1. Log in bash
```
docker exec -it symfony-api-php-fpm bash
```
and you can use bash to control application.

2. Run composer in php-fpm container:
```
php composer install
```
3. Run migrations
```
bin/console doctrine:migrations:migrate
```
4. Run fixtures
```
php bin/console doctrine:fixtures:load
```

Send requests to check result
-----------------------
1 . Endpoint for product price calculation. Request example:
```
curl --location 'http://symfonyapi.loc/api/calculate-price' \
--header 'Accept: application/json' \
--header 'Content-type: application/json' \
--data '{"productId": 4, "taxNumber": "FRVB123456789", "couponCode": "CF443"}'
```

2 . Endpoint for product purchase. Request example:
```
curl --location 'http://symfonyapi.loc/api/purchase' \
--header 'Accept: application/json' \
--header 'Content-type: application/json' \
--data '{"productId": 4, "taxNumber": "FRVB123456789", "couponCode": "CF443", "paymentProcessor": "paypal"}'
```

3 . To check tests run:
```
 php bin/phpunit
 ```

ENJOY PROJECT!!!
