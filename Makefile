PROJECT=inertia
DATABASE=inertia_db

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

mysql-drop:
	docker exec -it $(PROJECT)_db_1 bash -c "mysql -uroot -psecret -e 'drop database $(DATABASE);'"

mysql-create:
	docker exec -it $(PROJECT)_db_1 bash -c "mysql -uroot -psecret -e 'create database $(DATABASE);'"

mysql-load:
	docker exec -it $(PROJECT)_db_1 bash -c "mysql -uroot -psecret -e 'drop database $(DATABASE); create database $(DATABASE); use $(DATABASE); source dump.sql;'"

mysql-dump:
	docker exec -it $(PROJECT)_db_1 bash -c "mysqldump -uroot -psecret $(DATABASE) > dump.sql"

restart-supervisor:
	docker-compose exec app supervisorctl restart all

tinker:
	docker-compose exec app php -ti artisan tinker

composer-update:
	docker-compose exec app php composer.phar update

install:
	# condition isn't working
	# if [ -f .env ] && make up mysql-create setup

setup:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	# cp .env.example .env
	docker-compose build
	docker-compose up -d
	docker-compose exec app php composer.phar install
	docker-compose exec app php artisan key:generate
	# docker-compose exec app php artisan nova:publish
	# docker-compose exec app php composer.phar update
	docker-compose exec app php artisan migrate --seed
	docker-compose exec app php artisan storage:link
	docker-compose exec app supervisorctl reread
	docker-compose exec app supervisorctl update
	docker-compose exec app supervisorctl start laravel-worker:*
	sudo chgrp -R www-data .
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

update-production:
	php artisan down
	git pull
	php artisan migrate
	php artisan config:cache
	sudo supervisorctl restart laravel-worker:*
	php artisan up

export-localizations:
	php scripts/langToExcel.php

test:
	docker-compose exec app bash npm install $(packages)
