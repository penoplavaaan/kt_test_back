![Code Coverage](.github/badges/coverage.svg)

## Входные данные

CSV файл со структурой сотрудников компании со следующими полями:

- ФИО сотрудника
- ФИО руководителя

Иерархия сотрудников может быть многоуровневой, например:

Руководитель №1 → Подчинённый №11 → Подчинённый №111 → Подчинённый №1111

## Необходимо

- Реализовать форму загрузки файла
- Разработать структуру БД для хранения содержимого загруженного файла с сохранением связи “руководитель → подчинённый → подчинённый”
- При загрузке файла сохранить его содержимое в БД
- Реализовать страницу для отображения списка сотрудников. Как будет отображаться иерархия остаётся на выбор реализующего
- Реализовать на странице списка всех сотрудников возможность отображения подчинённых выбранного или введенного в форму фильтрации сотрудника (из примера выше, при поиске подчинённых для №11 должна быть получена иерархия из №111 и №1111)


## Запуск проета

1. docker-compose up -d --build
3. docker-compose exec php composer install
4. docker-compose exec php php bin/console doctrine:migrations:migrate