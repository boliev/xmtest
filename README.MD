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
docker-compose up
```