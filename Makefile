HTTPD_CONTAINER = pdf_editing_tool_httpd
PHP_CONTAINER = pdf_editing_tool_php
REQUIREMENTS = docker docker-compose vi npm node

check:
	$(foreach REQUIREMENT, $(REQUIREMENTS), \
		$(if $(shell command -v $(REQUIREMENT) 2> /dev/null), \
			$(info `$(REQUIREMENT)` has been found. OK!), \
			$(error Please install `$(REQUIREMENT)` before running setup.) \
		) \
	)

setup: check
	cp ./.env.local ./.env
	cp docker-compose.dev.yml docker-compose.override.yml
	vi ./.env
	vi docker-compose.override.yml
	docker-compose up -d --build
	docker exec $(HTTPD_CONTAINER) chmod -R 777 /var/www/pdf-editing-tool/storage
	docker exec $(PHP_CONTAINER) composer install --prefer-dist
	docker exec $(PHP_CONTAINER) php artisan key:generate
	make setup-tables
	make clear-cache

clear-cache:
	docker exec ${PHP_CONTAINER} php artisan optimize:clear
	docker exec ${PHP_CONTAINER} php artisan optimize
	docker exec ${PHP_CONTAINER} php artisan cache:clear
	docker exec ${PHP_CONTAINER} php artisan config:clear
	docker exec ${PHP_CONTAINER} php artisan route:clear
	docker exec ${PHP_CONTAINER} php artisan view:clear

migrate:
	docker exec ${PHP_CONTAINER} php artisan migrate

migrate-fresh:
	docker exec $(PHP_CONTAINER) php artisan migrate:fresh

update-setup:
	cp docker-compose.dev.yml docker-compose.override.yml
	docker-compose up -d --build

remove-setup:
	docker-compose down

setup-tables:
	docker exec $(PHP_CONTAINER) php artisan migrate:fresh --seed

bash:
	docker exec -it $(PHP_CONTAINER) bash