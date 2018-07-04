<?php

namespace App;


class Config
{
    /**
     * Хост базы данных
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Имя базы данных
     * @var string
     */
    const DB_NAME = 'framework';

    /**
     * Имя пользователя базы данных
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Пароль базы данных
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Показывать ошибки или нет
     * @var boolean
     */
    const SHOW_ERRORS = false;
}