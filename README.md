### Задача
Нужно реализовать API сервиc:

В сервисе хранятся статьи. К каждой статье могут привязываться теги.
Статья состоит из id и title. Тег из id и name

API cервиса должно позволять:
1) Создавать/редактировать тег. - запрос возвращает тег.
2) Создавать/редактировать статью. (с возможностью задать теги) - запрос должен вернуть статью.
3) Удалить статью (совсем, без softdelete)
4) Отдавать полный список статей. С возможностью фильтрации по тегам. В фильтре можно указать несколько тегов, должны отобразиться статьи у которых есть все указанные в фильтре теги. (с выводом всех тегов)
5) Статью по ID. (с выводом всех тегов)

### Требования
Реализовать можно c использованием любого фреймворка или на чистом php.
Формат входных и выходных данных - json.
Авторизацию делать не нужно.

### Реализация
Cервис реализован на базе фреймворка Lumen.

Стек:
* Docker+Docker Compose
* PHP 8.3 FPM
* Mysql 8.0
* Nginx 1.17

### Инструкция
Склонировать репозиторий

```bash
https://github.com/Simtel/rest-article-service
```

Cобрать контейнеры

```bash
make build
```

Запустить контейнеры
```bash
make up
```

Установить зависимости composer
```bash
make composer-install
```

Предыдущие шаги можно заменить командой 
```bash
make install
```

Установить миграции
```bash
make migrate-up
```

Заполнить таблицы данными
```bash
make db-seed
```

Запустить тесты
```bash
make test
```
