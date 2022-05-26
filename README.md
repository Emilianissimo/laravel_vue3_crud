# Laravel 9 and Vue.JS 3 CRUD realisation

This project created for demonstrating my Full-Stack abilities (in simpliest way).

Also it could be a nice tutorial example for people who have troubles, like me, with finding fine information about Vue+Laravel. Mostly Vue3. A lot people could say: "Don'd dell us whad do do! We already have dudorials!". So, I saw them. (0_0)

Whatever, here I'd solved the main issues, that you can find through you path.

- State working and request/reponse jobs
- Vue3 using with Laravel (installing 2nd by default, idk y)
- File uploading
- Other fancy stuff

Hope you will find it useful.

Wrote and tested on Ubuntu 20.04.4 LTS FF (Focal Fossa / focal).

To install project locally, clone it:

```bash
    git clone https://github.com/Emilianissimo/laravel_vue3_crud.git
```
Also you'll have to install Docker

```bash
    sudo apt-get install docker.io # for ubuntu
```

Create .env from .env.example and write credentials on your choice to connect MySQL DB, also repeat them into docker-compose.yml:

- MYSQL_DATABASE:
- MYSQL_ROOT_PASSWORD:

to give MySQL creds.

You'll no need to install any other packages like php/mysql/nginx in case of Docker including in project.

```bash
    make build # one first time
    # break after MySQL socket starts listening
    make start
```
Then you'll need to enter to your php bash space and run commands:

```bash
    php artisan migrate
    php artisan db:seed --class="CategorySeeder"
    php artisan db:seed --class="PostSeeder"
```

Those commands will generate you 10 categories with 50 attached posts. (Without images, do it yourself).

After that, just enter to your 127.0.0.1:8080 and feel free to do whatever you need.
