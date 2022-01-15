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
	docker exec -it rest-article-web sh -c "composer install"

migrate-up: ## Up MIgrate
	docker exec -it rest-article-web sh -c "php artisan migrate"

test: ##Run tests
	docker exec -it rest-article-web sh -c "./vendor/bin/phpunit"

