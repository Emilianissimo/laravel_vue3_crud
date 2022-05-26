start:
	docker-compose up -d
down:
	docker-compose down
stop:
	docker-compose stop
build:
	docker-compose up --build
mysql-bash:
	docker exec -it laravel_vue_mysql bash
php-bash:
	docker exec -it laravel_vue_php bash
nginx-bash:
	docker exec -it laravel_vue_nginx bash
