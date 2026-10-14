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