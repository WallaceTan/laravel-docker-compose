## Objectives:
1. Use PHP-FPM

    **php-fpm** is the FastCGI server implementation of PHP which you would use with a FastCGI compliant web server such as Apache or Nginx.

    **php-cli** is the standalone tool for running a PHP scripts in the same way you would run it from the command line: 
    ```
    php somescript.php
    ``` 
    It seems you can also use it to base other images from.

2. Create reusable PHP-FPM base image 

3. PHP-FPM base image to contain:
    * PHP-FPM
    * PHP-CLI
    * Composer
    * Laravel
    * Node
    * yarn

4. Development friendly, **hot reload** to view and test on file change.

5. Build static docker container for hosting on ECS without mounted volumes. 

6. Docker-compose commands
   ```bash
   cd ~/Projects/CFT/cft-portal-docker-test/
   docker compose -f docker-compose-app.yml up
   ```

7. php composer container to install php app dependencies from /app/composer.json to create 

    - https://stackoverflow.com/questions/51443557/how-to-install-php-composer-inside-a-docker-container
    
    7.1. Create new laravel project using PHP composer in a terminal with PHP and composer installed.

    ```bash
    $ composer create-project laravel/laravel="8.4.*" laravel
    ```

    7.2. Create new laravel project using PHP composer in a docker container

    Run PHP Composer (version 2.1.14) in a docker container.  
    ```bash
    $ docker run --name composer --rm --volume $(pwd)/admin-portal:/app:rw --workdir /app composer:2.1.14
    $ docker run --name composer --rm -v       $(pwd)/admin-portal:/app:rw -w        /app composer:2.1.14
    ```
    Docker run command options:
    ```
    $ docker run --help
    
    Usage:  docker run [OPTIONS] IMAGE [COMMAND] [ARG...]

    Options:
          --name string                    Assign a name to the container
          --rm                             Automatically remove the container when it exits
      -v, --volume list                    Bind mount a volume

    e.g.  --volume [source_dir_in_host]:[dest_dir_in_container]:[ro|rw]

    IMAGE: "composer:2.1.14"
    ```

    Use php composer to install laravel
    ```bash
    docker run --name composer --rm --volume $(pwd)/admin-portal:/app:rw --workdir /app composer:2.1.14 create-project laravel/laravel="8.4.*" .
    docker run --name composer --rm -v       $(pwd)/admin-portal:/app:rw -w        /app composer:2.1.14 create-project laravel/laravel="8.4.*" .
    ```

    ```bash
    docker run --name composer --rm --volume $(pwd)/admin-portal:/app:rw --workdir /app composer:2.1.14 install
    docker run --name composer --rm -v       $(pwd)/admin-portal:/app:rw -w        /app composer:2.1.14 install
    ```

8. Docker build
```bash
VERSION=2021.12.21
docker build --no-cache -f docker/php-fpm-alpine/Dockerfile -t php-fpm-alpine:${VERSION} .
docker compose -f docker-compose-app.yml build --no-cache
```
```
docker compose -f docker-compose-app.yml up -d
```

9. Open in web browser

    php  
    http://localhost:8081/

    pgAdmin 4  
    http://localhost:8082/  
        Username:  
        Password:

https://bitbucket.ship.gov.sg/projects/CLOUFILETR/repos/cft2-admin-portal/browse/docker/cft2/Dockerfile

References:
* [Docker setup for a Laravel+Vue project](https://medium.com/@crocodile2u/docker-setup-for-a-laravel-vue-project-90e4fd3acc7a)
* [Set up PHP, php-fpm and nginx for local development on docker](https://developpaper.com/set-up-php-php-fpm-and-nginx-for-local-development-on-docker/)
* [Setting up PHP, PHP-FPM and NGINX for local development on Docker
A primer on PHP on Docker under Windows 10.](https://www.pascallandau.com/blog/php-php-fpm-and-nginx-on-docker-in-windows-10/)
* [Running Headless Chrome with Puppeteer and Docker](https://vsupalov.com/headless-chrome-puppeteer-docker/)
* [How To Install and Set Up Laravel with Docker Compose on Ubuntu 20.04](https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-20-04)

* [How to run docker-compose with php-fpm and php-cli?](https://stackoverflow.com/questions/42392557/how-to-run-docker-compose-with-php-fpm-and-php-cli)

    PHP cli is not running in daemon mode. You run it, and then it stops. Next, Docker tries to restart it (you've set restart: always policy for php-cli). :)

    IMO php-cli and composer services are redundant. You can use php service for your needs. Simply run 
    ```
    docker-compose run php php [path to script]
    ```
