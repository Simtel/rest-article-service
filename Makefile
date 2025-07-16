.DEFAULT_GOAL := help

help: ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

up: ## Up containers
	@docker-compose up -d --remove-orphans
	@echo -e "Make: Up containers.\n"

build: ## Build containers
	docker-compose build
	@echo -e "Make: Up containers.\n"

down: ## Down containers
	@docker-compose down

stop: ## Stop contrainers
	@docker-compose stop

install: build up env composer-install

env: ##Copy env file
	cp .env.example .env
restart: stop up ## Restart docker containers

mysql-console: ## Mysql Console Failed
	@docker exec -it rest-article-db /usr/bin/mysql -uroot -pexample

php-console: ## PHP console
	docker exec -it --user www-data rest-article-web bash

composer-install: ##Install composer packages
	docker exec -it --user www-data rest-article-web sh -c "composer install"

migrate: ##Migrate
	docker exec -it --user www-data rest-article-web sh -c "php artisan migrate"
	docker exec -it --user www-data rest-article-web sh -c "php artisan migrate --env=testing"

test-db-refresh: ##Refresh test database
	docker exec -it --user www-data rest-article-web sh -c "php artisan migrate:refresh --env=testing"

test: ##Run tests
	docker exec -it --user www-data rest-article-web sh -c "./vendor/bin/phpunit"

testdox: ##Run tests
	docker exec -it --user www-data rest-article-web sh -c "./vendor/bin/phpunit --testdox"

db-seed: ##Run seeders
	docker exec -it --user www-data rest-article-web sh -c "php artisan db:seed"

rector:
	docker exec -it --user www-data rest-article-web sh -c "./vendor/bin/rector process app"

pint:
	docker exec -it --user www-data rest-article-web sh -c "./vendor/bin/pint"

phpstan:
	docker exec -it --user www-data rest-article-web sh -c "./vendor/bin/phpstan analyze -v --memory-limit=2G"
