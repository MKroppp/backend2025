# Онлайн библиотека

API для онлайн библиотеки, Регистрация, авторизация, управление книгами и добавление книги в избранное с использованием Lumen PHP Framework.

### Основной функционал

1. **Авторизация:**
   - Регистрация с почтой и паролем.
   - Логин с почтой и паролем.
   
2. **Книги:**
   - Просмотр списка всех доступных книг (не требует авторизации).
   - Получение информации по отдельной книге (не требует авторизации).
   - Добавление новой книги (требует логин и роль администратора).
   - Удаление книги (требует логин и роль администратора).
   - Добавление книги в избранное (требует логин).
   - Удаление книги из избранного (требует логин).
   - Выгрузка списка книг в CSV формат (требует роль администратора).

3. **Роли:**
   - Возможность изменять роль пользователя с помощью консольной команды.
   - Доступные роли: Администратор и Клиент.

## Установка

1. Клонировать репозиторий:
   ```bash
   git clone https://github.com/MKroppp/backend2025.git

2. Установить зависимости:
   ```bash
    cd backend2025
    composer install

3. Настроить .env файл, указав параметры для подключения к базе данных PostgreSQL.

4. Выполнить миграции:
   ```bash
   php artisan migrate

5. Запустить сервер:
   ```bash
   php -S localhost:8000 -t public

## Проверка с использованием Apidog

Для тестирования API в проекте используется инструмент **Apidog**, который позволяет легко создавать запросы, тестировать различные эндпоинты и анализировать ответы.

  **Пример запросов:**
   - **GET /books**: Получить список всех доступных книг (не требует авторизации).
   - **GET /books/{id}**: Получить информацию по отдельной книге (не требует авторизации).
   - **POST /register**: Регистрация нового пользователя с почтой и паролем.
   - **POST /login**: Логин пользователя и получение токена.
   - **POST /books**: Добавление новой книги (требует роль администратора).
   - **POST /favorites**: Добавление книги в избранное (требует авторизацию).
   - **DELETE /favorites/{book_id}**: Удаление книги из избранного (требует авторизацию).
   - Для изменения роли юзера:
     ```bash
     php artisan user:change-role 1 admin
     
## Тестовое задание:
1. bool(false)
2. bool(true)
3. 10
4. 10
5. Вылетит ошибка.
6. 5
7. 10
8. Вылетит ошибка.
9. Выполнится успешно.
10. Dog move Animal move
11. Animal breath Animal breath
12. Установит все зависимости согласно composer.lock (если есть) в vendor директорию, иначе использует composer.json и после создаст composer.lock файл.
13. Установит все зависимости согласно composer.json (даже если есть composer.lock файл) в vendor директорию, после обновит composer.lock.
14. composer require <имя пакета>
15. 1.2.X
16. ```sql
    SELECT
    a.id AS article_id,
    a.title AS article_title,
    a.body AS article_body,
    c.id AS comment_id,
    c.title AS comment_title,
    c.body AS comment_body,
    c.parent_id AS comment_parent_id
    FROM
        articles a
            JOIN
        comments c ON a.id = c.article_id
    WHERE
        a.id = 101;
17. ```sql
    SELECT
    u.id AS user_id,
    u.email,
    p.first_name,
    p.last_name,
    p.photo_link,
    r.title AS role_title
    FROM
        users u
            JOIN
        profiles p ON u.id = p.user_id
            JOIN
        users_roles ur ON u.id = ur.user_id
            JOIN
        roles r ON ur.role_id = r.id
    WHERE
        u.id = 256;
18. ```sql
    INSERT INTO users_roles (user_id, role_id)
    VALUES (225, 8);
19. ```sql
    UPDATE profiles
    SET photo_link = 'new_photo_link'
    WHERE user_id = 67;
20. ```sql
    DELETE FROM users
    WHERE id = 78;

