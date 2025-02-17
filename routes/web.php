<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return '';
});

// Роуты для регистрации и логина
$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');

// Роуты для книг
$router->get('books', 'BookController@index'); // Все книги
$router->get('books/csv', 'BookCsvController@exportCsv'); // Экспорт книг в CSV (только для админа)
$router->get('books/{id}', 'BookController@show'); // Получить книгу по ID

// Роуты, которые требуют аутентификации и проверки ролей
$router->post('books', 'BookController@store'); // Добавить книгу (только для админа)
$router->delete('books/{id}', 'BookController@destroy'); // Удалить книгу (только для админа)

$router->post('books/{id}/favorites', 'BookController@addToFavorites'); // Добавить книгу в избранное
$router->delete('books/{id}/favorites', 'BookController@removeFromFavorites'); // Удалить книгу из избранного

// Роуты для работы с ролями
$router->put('users/{id}/role', 'RoleController@changeRole'); // Изменить роль пользователя

// Роуты для жанров
$router->get('genres', 'GenreController@index'); // Все жанры
$router->get('genres/{id}', 'GenreController@show'); // Получить жанр по ID
