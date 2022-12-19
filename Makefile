up-d: ## Поднять контейнеры
	docker-compose up -d

build: ## Поднять контейнеры
	docker-compose up -d --build && docker-compose exec php ./vendor/bin/phpunit --coverage-html html

test:
	docker-compose exec php composer test
#&& docker-compose exec php  composer update-badges

server: ## Войти в контейнер сервера
	docker-compose exec php bash

ps: ## Состояние контейнеров
	docker-compose ps

logs: ## Логи
	docker-compose logs

rs: ## Рестарт контейнеров
	docker-compose restart

help: ## Справка
	@egrep -h '\s##\s' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

down: ## Остановить все контейнеры
	docker-compose down
