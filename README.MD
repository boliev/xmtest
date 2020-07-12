# Introduction
This solution contains two separate parts backend and frontend. Both parts work via docker.

Frontend part:
 - Nginx
 - front-end framework: materializecss (https://materializecss.com)
 - JS: jQuery
 - charts: Chart.js (https://www.chartjs.org/) 
 
Backend part:
 - PHP 7.4
 - Symfony 5.1
 - Nginx
 - Redis
 
# Installation
To fetch the code
```
git clone https://github.com/boliev/xmtest.git
```

Now you need to start the containers
```
cd xmtest
docker-compose up -d
```

Now you have four containers running:
```
docker ps -a
```
```
CONTAINER ID        IMAGE               COMMAND                      PORTS                    NAMES
1674513d1581        nginx:latest        "/bin/bash -c 'exec …"       0.0.0.0:8882->80/tcp     xmtest_nginx_1
cfbda2db58df        redis:latest        "docker-entrypoint.s…"       0.0.0.0:6399->6379/tcp   xmtest_redis_1
e46f391ee27f        xmtest_php          "docker-php-entrypoi…"       9000/tcp                 xmtest_php_1
a956426085e7        nginx:latest        "/bin/bash -c 'exec …"       0.0.0.0:8881->80/tcp     xmtest_front_1
```

`xmtest_front_1` - container for frontent nginx

`xmtest_nginx_1` - container for backend nginx

`xmtest_php_1` - container for frontent php

`xmtest_redis_1` - container for frontent redis

Get into the php container and install composer dependencies
```
docker exec -ti xmtest_php_1 bash
composer install
```

Let's run tests inside the container
```
bin/phpunit tests/
```

Also you could config the mailer by editing back/.env
```
MAILER_URL=smtp://host.docker.internal:1025
```

That's all. Now you can open http://localhost:8881/ to see the interface. 
The API is accessible by http://localhost:8882/.

# API
There are three endpoints
### Get Quotations and notify user by email
```
[GET] http://localhost:8882/v1/quotations?company=Goog&email=voov@fff.com&startDate=2020-07-05&endDate=2020-07-10
```
Parameters:
 - `company` - company symbol
 - `email` - email to notify
 - `startDate` - start date (yyyy-mm-dd)
 - `endDate` - end date (yyyy-mm-dd)
 
Responses:

200 - OK
 ```
{
    "company": {
        "name": "Google Inc.",
        "symbol": "GOOG"
    },
    "quotations": [
        {
            "date": "2020-07-07T13:30:00+00:00",
            "open": 1490,
            "close": 1485.1800537109375,
            "high": 1516.800048828125,
            "volume": 1458200,
            "low": 1483.550048828125
        },
        {
            "date": "2020-07-06T13:30:00+00:00",
            "open": 1480.06005859375,
            "close": 1495.699951171875,
            "high": 1506.5899658203125,
            "volume": 1564000,
            "low": 1472.8599853515625
        }
    ]
}
```
 
400 - bad request
 ```
{
    "errors": [
        "Invalid email address"
    ]
}
```
```
{
    "errors": [
        "End Date must be greater or equal than Start Date"
    ]
}
```
```
{
    "errors": [
        "Start Date in the future"
    ]
}
```

### Get company
```
[GET] http://localhost:8882/v1/company/{Company_symbol}
```
Responses:
 
200 - OK
 ```
{
    "name": "Google Inc.",
    "symbol": "GOOG"
}
```
404 - company not found
```
{
    "errors": [
        "Company not found"
    ]
}
```

### Search company (for autocomplete)
```
[GET] http://localhost:8882/v1/company/search/{some_string}
```
Responses:
 
200 - OK
 ```
[
    {
        "name": "Gladstone Commercial Corporation",
        "symbol": "GOOD"
    },
    {
        "name": "Gladstone Commercial Corporation",
        "symbol": "GOODN"
    },
    {
        "name": "Gladstone Commercial Corporation",
        "symbol": "GOODO"
    },
    {
        "name": "Gladstone Commercial Corporation",
        "symbol": "GOODP"
    },
    {
        "name": "Google Inc.",
        "symbol": "GOOG"
    },
    {
        "name": "Google Inc.",
        "symbol": "GOOGL"
    }
]
```
If not found any
```
[]
```