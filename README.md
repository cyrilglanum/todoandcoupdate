
# TODO&CO

TODO & CO est un site permettant de gérer des tâche quotidiennes et la mises à jour de celles-ci sous 
Symfony.

## Sommaire documentation sur le projet

[Documentation roles](Documentation/Roles.md)

### Documentation Technique

[Documentation technique](Documentation/Documentation_technique.md)

### Contribution
[Contribution](Documentation/Contribution.md)
## Local

#### Cloner le projet

```bash
  git clone https://github.com/cyrilglanum/todoandco.git
```

#### Aller sur le répertoire du projet

```bash
  cd todoandco
```

#### Installer les dépendances

```bash
  php composer install
```

#### Configurer le fichier .env


#### Optimizing Configuration Loading

```bash
  php bin/console clear:cache
  php bin/console config:cache
```

## Vhosts
Exemple Wamp
```
#
<VirtualHost *:80>
	ServerName local.todoandco.com
	DocumentRoot "c:/wamp64/www/todoandco/public/"
	<Directory  "c:/wamp64/www/todoandco/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>g
</VirtualHost>
```

#### Database
Créer la base de données "todoandco" et y insérer le fichier SQL ci-dessous.

[todoandco - todoandco.sql](Documentation/todoandco_sql_files.zip) 

ou lancer les fixtures avec la commande 

```
php bin/console doctrine:fixtures:load
```

#### Database de test PHPunit
Créer la base de données "todoandco_test" et y insérer le fichier SQL ci-dessous.

[todoandco_test - todoandco_test.sql](Documentation/todoandco_sql_files.zip) 


```bash
Importer le fichier SQL ci-dessus dans la base de données pour importer 
toutes les données de test.
```

