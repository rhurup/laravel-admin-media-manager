# Forked from laravel-admin-extensions/media-manager

--------------

Media manager for laravel-admin
===============================

Media manager for `local` disk.

[Documentation](http://laravel-admin.org/docs/#/en/extension-media-manager) | [中文文档](http://laravel-admin.org/docs/#/zh/extension-media-manager)

## Screenshot

![wx20170809-170104](https://user-images.githubusercontent.com/1479100/29113762-99886c32-7d24-11e7-922d-5981a5849c7a.png)

## Installation

```shell
composer require rhurup/laravel-admin-media-manager

php artisan admin:import media-manager
```

Add a disk config in `config/admin.php`:

```php

    'extensions' => [

        'media-manager' => [

            // Select a local disk that you configured in `config/filesystem.php`
            'disk' => 'public',
            'allowed_ext' => 'jpg,jpeg,png,pdf,doc,docx,zip'
        ],
    ],

```


Open `http://localhost/admin/media`.

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
