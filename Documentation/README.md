
# TODO&CO

TODO & CO est un site permettant de gérer des tâche quotidiennes et la mises à jour de celles-ci sous 
Symfony.

## Sommaire documentation sur le projet

[Documentation roles](Roles.md)

### Documentation Technique

[Documentation technique](Documentation_technique.md)

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
	</Directory>
</VirtualHost>
```

#### Database
[//]: # (TODO)
[todoandco](bilemoapi.sql) 


```bash
Importer le fichier SQL ci-dessus dans la base de données pour importer 
toutes les données de test.
```

