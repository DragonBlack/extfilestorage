# ExtFileStorage for Yii2

## Установка

Для установки данного расширения необходимо наличие установленного composer'а (http://getcomposer.org/download/).

В файл `composer.json` добавьте строки:

```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/DragonBlack/extfilestorage.git"
    }
]
```

Выполните в командной строке

```bash
$ composer require dragonblack/extfilestorage:@dev
```

или добавьте

```
"dragonblack/extfilestorage": "@dev"
```

в секцию `require` Вашего файла `composer.json`

## Настройка

Добавьте ключи приложений в файл параметров Yii2

```php
return [
    'efsdropbox_appkey' => '##########',       //App key приложения в Dropbox
    'efsdropbox_appsecret' => '###########',   //App secret приложения в Dropbox
    'efspicker_devkey' => '##########-######', //API key приложения Google
    'efspicker_clientid' => '########-#######.apps.googleusercontent.com',  //Client ID приложения Google
];