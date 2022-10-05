# Documentation technique Todo&Co

## Mise en place de la migration

Une retouche a été nécessaire pour voir ce qu'il fallait refaire pour apporter une migration de 
version majeure de symfony.

Il a donc été fait une opération de compréhension de l'architecture initiale du projet pour 
permettre de prévoir la nouvelle architecture qui allait se mettre en place pour l'application améliorée .
A partir de là, le projet a été recréé, la mise en place de la structure base de données remise en place et tout
cela sous symfony 5.4.

## Authentification

Au niveau de l'```authentification```, elle a donc été implémentée en reprenant l'architecture initiale du projet
et en respectant la documentation de Symfony prévue à l'effet.

Le fichier de configuration de l'authentification est dans le fichier 
``` config/packages/security.yaml ```
Au niveau du fichier security, se trouvent les providers qui permettent de définir quelle classe sera en charge d'être authentifiée, 
à savoir la classe ```App\Entity\User``` .

Les utilisateurs implémentent UserInterface pour avoir les méthodes parent de ```UserInterface``` et ```PasswordAuthenticatedUserInterface``` qui permettent d'avoir un cryptage
du mot de passe conséquent et une authentification sécurisée.

Il a été mis en place l'```AppAuthenticator``` pour fournir un accès unique en fonction 
des 3 éléments qui sont l'email, le password et le token csrf pour l'utilisateur qui souhaite se connecter.
Il a remplacé le système d'authentification classique de la documentation car son fonctionnement était un peu trop
"caché" à mon sens.

```cf : fonction authenticate -> src/Security/AppAuthenticator.php```

## Process d'authentification

Pour comprendre tout le process :

*   L'utilisateur essaye d'accéder à une ressource protégée.

*   Le firewall initie un process d'authentication en redirigeant l'utilisateur sur le formulaire de login.

*   La page login est rendue à l'écran.

*   L'utilisateur soumet ses informations.

*   Le système de sécurité intercepte la requête, contrôle les informations et authentifie l'utilisateur
si elles sont correctes ou renvoie sur le formulaire de login sinon.

Les utilisateurs sont stockés ensuite dans le système d'authentification de Symfony Auth. Ils sont utilisables en utilisant le code 

``` $this->getUser()``` dans les controllers.

## Roles

```Role_user``` Ce rôle permettra de naviguer sur le site étant authentifié et pourra crééer, modifier ses tâches

```Role_admin``` Ce rôle aura tous les droits entre la navigation, la vérification et pourra bien sûr
modifier, supprimer n'importe quelle tâche. 

### Tests unitaires, fonctionnels et couverture de code

Les tests unitaires et fonctionnels ont été mis en place dans le dossier ```test```, veillez à continuer à implémenter les tests
pour avoir une application fonctionnelle et sûre vis-à-vis du taux de couverture de code.

Pour tous les fichiers de couverture de code, ils sont disponibles dans le dossier ``` public/test-coverage```

## Issues, organisation

https://trello.com/b/qxSHvsP1/todoandco