# How to start with docker

1. Build the containers

```shell
$ docker-compose build
```

2. Spin the containers

```shell
$ docker-compose up nginx
```

3. Migrate the database

```shell
$ bash bin/artisan.sh migrate:fresh
```

4. Copy env.example

```shell
$ cd src & cp .env.example .env
```

5. Visit localhost


# How to start without docker

If you don't use docker you will need only need the src folder. In the src folder is a normal Laravel application. 
To run it, start your web server and run the migrations.