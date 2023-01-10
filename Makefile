up-d: ## Поднять контейнеры
	docker-compose up -d

build: ## Сбилдить контейнеры
	docker-compose up -d --build && make migrate && make test

migrate: ## Миграции
	docker-compose exec php composer migrate

badge: ## Обновление бейджика покрытия
	rm -f .github/badges/coverage.svg && docker-compose exec php composer update-badges

test: ## Запуск тестов
	docker-compose exec php composer test && make badge

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
