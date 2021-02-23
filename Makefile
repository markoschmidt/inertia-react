include .env
DB_DUMP=dump.sql

PROJECT=$(notdir $(CURDIR))# project directory name

php:
	docker-compose exec app bash

db:
	docker-compose exec db bash

web:
	docker-compose exec web bash

up:
	docker-compose up -d

down:
	docker-compose down

stop:
	docker-compose stop

build:
	docker-compose build

make restart:
	docker-compose restart

mysql-drop:
	@docker exec -it $(PROJECT)_db_1 bash -c "mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -e 'drop database $(DB_DATABASE);' > /dev/null 2>&1"

mysql-create:
	@docker exec -it $(PROJECT)_db_1 bash -c "mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -e 'create database $(DB_DATABASE);' > /dev/null 2>&1"

mysql-refresh:
	docker exec -it $(PROJECT)_db_1 bash -c "mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -e 'drop database $(DB_DATABASE); create database $(DB_DATABASE); use $(DB_DATABASE); source $(DB_DUMP);'"

mysql-load:
	docker exec -it $(PROJECT)_db_1 bash -c "mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) -e 'use $(DB_DATABASE); source $(DB_DUMP);'"

mysql-dump:
	docker exec -it $(PROJECT)_db_1 bash -c "mysqldump -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) > $(DB_DUMP)"

migrate:
	docker-compose exec app php artisan migrate

restart-supervisor:
	docker-compose exec app supervisorctl restart all

tinker:
	docker-compose exec app php -ti artisan tinker

composer-update:
	docker-compose exec app php composer.phar update

install:
ifdef APP_NAME
	make up mysql-drop mysql-create setup
else
	echo .env is probably missing from the root folder
endif

setup:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	docker-compose build
	docker-compose up -d
	docker-compose exec app php composer.phar install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate --seed
	docker-compose exec app php artisan storage:link
	docker-compose exec app supervisorctl reread
	docker-compose exec app supervisorctl update
	docker-compose exec app supervisorctl start laravel-worker:*
	sudo chgrp -R www-data .
	sudo chown -R 1000 .
	sudo find . -path ./.git -prune \
		-o -path ./node_modules -prune \
		-o -path ./vendor -prune \
		-o -type f -not -perm 644 -exec chmod 644 {} \;
	sudo find . -path ./.git -prune \
		-o -path ./node_modules -prune \
		-o -path ./vendor -prune \
		-o -type d -not -perm 775 -exec chmod 775 {} \;
	sudo chmod -R 774 storage bootstrap/cache
	git checkout app
	git checkout config

files:
	sudo chown -R 1000:www-data .
	sudo find . -path ./.git -prune \
		-o -path ./node_modules -prune \
		-o -path ./vendor -prune \
		-o -type f -not -perm 644 -exec chmod 644 {} \;
	sudo find . -path ./.git -prune \
		-o -path ./node_modules -prune \
		-o -path ./vendor -prune \
		-o -type d -not -perm 775 -exec chmod 775 {} \;
	sudo chmod -R 774 storage bootstrap/cache
