Konfiguracja Composera
======================

Po zainstalowaniu szablonu projektu dobrze jest zmodyfikować domyślny plik `composer.json`, znajdujący się w głównym 
folderze:

```json
{
    "name": "yiisoft/yii2-app-advanced",
    "description": "Yii 2 Advanced Project Template",
    "keywords": ["yii2", "framework", "advanced", "project template"],
    "homepage": "https://www.yiiframework.com/",
    "type": "project",
    "license": "BSD-3-Clause",
    "support": {
        "issues": "https://github.com/yiisoft/yii2/issues?state=open",
        "forum": "https://www.yiiframework.com/forum/",
        "wiki": "https://www.yiiframework.com/wiki/",
        "irc": "ircs://irc.libera.chat:6697/yii",
        "source": "https://github.com/yiisoft/yii2"
    },
    "minimum-stability": "dev",
    "require": {
        "php": ">=7.4.0",
        "yiisoft/yii2": "~2.0.45",
        "yiisoft/yii2-bootstrap5": "~2.0.2",
        "yiisoft/yii2-symfonymailer": "~2.0.3"
    },
    "require-dev": {
        "yiisoft/yii2-debug": "~2.1.0",
        "yiisoft/yii2-gii": "~2.2.0",
        "yiisoft/yii2-faker": "~2.0.0",
        "phpunit/phpunit": "~9.5.0",
        "codeception/codeception": "^5.0.0 || ^4.0",
        "codeception/lib-innerbrowser": "^4.0 || ^3.0 || ^1.1",
        "codeception/module-asserts": "^3.0 || ^1.1",
        "codeception/module-yii2": "^1.1",
        "codeception/module-filesystem": "^3.0 || ^2.0 || ^1.1",
        "codeception/verify": "^3.0 || ^2.2",
        "symfony/browser-kit": "^6.0 || >=2.7 <=4.2.4"
    },
    "autoload-dev": {
        "psr-4": {
            "common\\tests\\": ["common/tests/", "common/tests/_support"],
            "backend\\tests\\": ["backend/tests/", "backend/tests/_support"],
            "frontend\\tests\\": ["frontend/tests/", "frontend/tests/_support"]
        }
    },
    "config": {
        "allow-plugins": {
            "yiisoft/yii2-composer" : true
        },
        "process-timeout": 1800,
        "fxp-asset": {
            "enabled": false
        }
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://asset-packagist.org"
        }
    ]
}
```

Zacznijmy od podstawowych informacji. Zmień `name` (nazwę), `description` (opis), `keywords` (słowa kluczowe), 
`homepage` (stronę domową) i `support` (adresy serwisów wsparcia projektu) na odpowiednie dla Twojego projektu.

Teraz czas na interesującą część - w sekcji `require` możesz dodać więcej pakietów zależności, których wymaga Twoja 
aplikacja. Pakiety te są pobierane poprzez serwis [packagist.org](https://packagist.org/) - zerknij na jego zasoby 
w poszukiwaniu przydatnego kodu.

Po aktualizacji pliku `composer.json` możesz uruchomić komendę `composer update --prefer-dist` - nowe pakiety zostaną 
pobrane i zainstalowane i będą od razu gotowe do użycia. Autoładowanie klas jest obsługiwane automatycznie.