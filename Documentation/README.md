
# BILEMO API

Bilemo API est une api de ressources de l'entreprise BileMo offrant des gammes de smartphone.

L'API propose des "endpoints", décris comme points de terminaison qui permettront de rechercher
des informations en fonction de requêtes effectuées sur des url spécifiées.


## Sommaire documentation sur le projet

[Documentation roles](Roles.md)

## Local

#### Cloner le projet

```bash
  git clone https://github.com/cyrilglanum/API-BileMo.git
```

#### Aller sur le répertoire du projet

```bash
  cd bilemoAPI
```

#### Installer les dépendances

```bash
  php composer.phar install
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
	ServerName local.bilemo.com
	DocumentRoot "c:/wamp64/www/bilemoApi/public/"
	<Directory  "c:/wamp64/www/bilemoApi/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>
```

#### Database
[Bilemo SQL](bilemoapi.sql) 

```bash
Importer le fichier SQL ci-dessus dans la base de données pour importer 
toutes les données de test.
```

### Documentation API - URL 

```bash
/documentation/api
```