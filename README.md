## Installation

### Datenbank

Migrationen erstellen

```
php vendor/bin/doctrine-migrations diff
```

Migrationen ausführen

```
php vendor/bin/doctrine-migrations migrate
```

Generate Database proxies

```
php vendor/bin/doctrine orm:generate-proxies
```