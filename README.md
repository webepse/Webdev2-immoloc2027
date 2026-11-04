# Installation de Symfony 

```symfony new immoloc2027 --webapp```

## Supprimer AssetMapper

```composer remove symfony/ux-turbo symfony/asset-mapper symfony/stimulus-bundle```

## installer webpack ENCORE

```composer require symfony/webpack-encore-bundle symfony/ux-turbo symfony/stimulus-bundle```

```npm install```

## démarrer le symfony

```symfony server:start```

## installer bootstrap

```npm install bootstrap@5.3.8```

installation de bootstrap icons

```npm install bootstrap-icons --save-dev```

## créer la bdd

modifier le .env

__Pour PC__ 

```DATABASE_URL="mysql://root:@127.0.0.1:3306/studentimmoloc27?serverVersion=8.0.32&charset=utf8mb4"```

__Pour Mac__

```DATABASE_URL="mysql://root:root@127.0.0.1:8889/studentimmoloc27?serverVersion=8.0.32&charset=utf8mb4"```

pour créer la bdd

```php bin/console doctrine:database:create```

## installation des fixtures

```composer require orm-fixtures --dev```

```composer require fakerphp/faker```

```composer require cocur/slugify```

# Procédure d'installation via GitHub d'un projet déjà créé


### clone le repository
```git clone https://github.com/webepse/Webdev2-immoloc2027.git```

### rentrer dans le dossier
```cd Webdev2-immoloc2027```

### installation des dépendances de composer (composer.json)
```composer install``` ou ```composer i```

### installation des dépendances de npm (package.json)
```npm install``` ou ```npm i```

### rentrer dans le dossier via votre IDE

exemple avec VSCODE
```code .```

### création de la base de données (voir .env)

Création de la bdd

```php bin/console doctrine:database:create```

Envoyer les tables

```php bin/console doctrine:migrations:migrate```

Remplir les données

```php bin/console doctrine:fixtures:load```

### démarrer les serveurs

Serveur php

```symfony server:start```

Serveur JS

```npm run watch```