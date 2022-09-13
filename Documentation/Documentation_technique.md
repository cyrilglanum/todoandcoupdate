
# Documentation technique Todo&Co

### Mise en place de la migration

Une retouche a été nécessaire pour voir ce qu'il fallait refaire pour apporter une migration de 
version majeur de symfony.

Il a donc été fait une opération de compréhension de l'architecture initiale du projet pour 
permettre de prévoir la nouvelle architecture qui allait se mettre en place pour l'application améliorée
.
A partir de là, le projet a été recréé et la mise en place de la structure base de données remise en place et tout
cela sous symfony 5.4.

### Authentification

Au niveau de l'authentification, elle a donc été implémentée en reprenant l'architecture initiale du projet
et en respectant la documentation de Symfony prévue à l'effet.

Il a été mis en place l'AppAuthenticator pour fournir un accès unique en fonction 
des 3 éléments qui sont l'email, le password et le token csrf.
Il a remplacé le système d'authentification classique de la documentation.

cf : fonction authenticate -> src/Security/AppAuthenticator.php

Pour comprendre tout le process :
- l'utilisateur essaye d'accéder à une ressource protégée
- Le firewall initie un process d'authentication en redirigeant l'utilisateur sur le formulaire de login
- la page login est rendue à l'écran
- L'utilisateur soumet ses informations
- le système de sécurité intercepte la requête, contrôle les informations et authentifie l'utilisateur
si elles sont correctes ou renvoie sur le formulaire de login sinon.


#### Roles

[Role_user] -> Ce rôle permettra de naviguer sur le site étant authentifié et pourra crééer modifier ses tâches

[Role_admin] -> Ce rôle aura tous les droits entre la navigation, la vérification et pourra bien sur
modifier, supprimer n'importe quelle tâche. 





