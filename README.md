# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Данный проект реализует один метод POST который записывает или обновляет данные
продукции:
{
"product": [{
"unid": "100108",
"quantity": "1",
"scu": "scu_1",
"barcode": "5611165446415",
"warehouse": "Склад на Софии",
"size": "L"
}]
}

проверять возможность приема запросов от источника (принимать запрос только при наличии ключа);
Ключи находятся в таблице User поле key
Наличие ключа позволяет пользователю работать с добавлением /редактированием товара и склада где он находиться
(Склад если не существует - добавляется новый)
Таблица склада и таблица продуктов связаны между собою.

БД помимо миграций добавлена выгрузка структуры.

Для ручной инсталляции необходимо выполнить :
1) Создать БД (название не принципиально , главное чтобы все настройки были в файле .env (файл с примером прилагается)
2) Сгенерировать токены или заполнить 32 символа в APP_KEY=094567124734873456532645194634526732902 файла .env
3) Установить таблица из файла sql.sql (что в корне проекта)
4) Создать пользователя (принципиально можно заполнить его Имя и ключ key который быдет служить ключем токена)
5) По возможности создать склады и товары, или сразу с помощью POSTMAN отправить :
5.1) Заголовки Headers c Ключем "Authorization",(без кавычек).
5.2) Значением сгенерированного ТОКЕНА из БД User поля key.
5.3) В тело (Body) вставить JSON структуру (см. выше).
5.4) Заполнить поле отправки согласно Вашего сайта или локального домена http://YOUR-HOST/api/product
6) В случае неправильного ТОКЕНА будет ошибка вида 403 - Доступ запрещен
В случае успеха 201 {'error':'no'} 

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
