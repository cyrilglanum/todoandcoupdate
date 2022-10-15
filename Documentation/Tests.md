# Documentation technique Tests PHPunit Todo&Co

## Mise en place des bdd de tests

Pour le lancement des tests il faudra lancer quelques commandes pour que cela purge les base de données et puisse lancer les tests sur
des environnements sains.

Allez dans le terminal et effectuez ces commandes dans le bon ordre.

### Base de données initiale

```php bin/console doctrine:database:drop --force```

```php bin/console doctrine:database:create```

```php bin/console doctrine:schema:create```

Et le lancement des fixtures.

```php bin/console doctrine:fixtures:load```

Repondre Oui à la demande de purger la database.

### Base de données de test pour phpUnit

```php bin/console --env=test doctrine:database:drop --force```

```php bin/console --env=test doctrine:database:create```

```php bin/console --env=test doctrine:schema:create```

Et le lancement des fixtures dans la base de données de test.

```php bin/console --env=test doctrine:fixtures:load```

Repondre Oui à la demande de purger la database.
 
Enfin pour lancer les tests via php unit, lancez la commande 

```php bin/phpunit```

Cela lancera tous les tests et vous pourrez vérifier le bon déroulement des tests ainsi que le nombre
de tests et d'assertions données.
