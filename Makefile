up-d: ## Поднять контейнеры
	docker-compose up -d

build: ## Сбилдить контейнеры
	docker-compose up -d --build && docker-compose exec php ./vendor/bin/phpunit --coverage-html html

migrate: ## Миграции
	docker-compose exec php composer migrate

test: ##Тест html
	rm -f .phpunit.result.cache && rm -f -R ./html && docker-compose exec php composer test

test-xml: ##Тест xml
	rm -f .phpunit.result.cache && rm -f coverage.xml && docker-compose exec php composer test-xml

badge: ## Обновление бейджика покрытия
	rm -f .github/badges/coverage.svg && docker-compose exec php composer update-badges

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
