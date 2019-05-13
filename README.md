# Développement avec un framework Symfony

## Les frameworks

Même si chaque site est unique, on retrouve sur chacun d'entre eux de nombreuses fonctionnalités similaires, des tâches récurrentes, et des architectures semblables. On a vu dans le projet précédent que la **programmation orientée objet** pouvait nous aider, en nous permettant par exemple d'**encapsuler des comportements** dans des classes pour **isoler** les problématiques, améliorer la **testabilité** du code, et faciliter sa **réutilisation**.

Par exemple, nous avons développé une classe `Database` permettant de se connecter à une base de données, puis des classes `Query` permettant de faire des requêtes à cette base. Les classes sont donc interconnectées, par des notions d'_héritage_, de _composition_, et/ou de _dépendance_, et au fur à mesure que le projet grossit, le nombre de classes et d'inter-connexions augmente, rendant la compréhension du système difficile, car on peut difficilement tout garder en tête.  

Il est donc nécessaire de faire émerger une **structure** dans ces liens entre les classes. Cela peut par exemple passer par une organisation en sous-dossiers, mais aussi par une séparation des classes par rôles. Certaines classes sont dédiées à l'affichage des données, d'autres à leur transformation, et d'autres à leur stockage. 

> Ces problématiques sont communes, et de nombreux développeurs y ont déjà été confrontés.

Les frameworks sont des solutions "clés en main", mettant à disposition du développeur un ensemble de fonctions, de classes, et d'outils au sens large lui permettant de développer son application rapidement en se concentrant sur la logique métier. Pas besoin de créer une classe pour gérer la base de données, elle est déjà dans le framework. Inutile de créer un système de routing "à la main", le framework propose déjà une solution pour ce problème.

L'utilisation d'un framework offre donc plusieurs avantages, car c'est :

- une solution **complète** avec des outils pour gagner en productivité 
- une solution **abstraite** permettant de développer n'importe quel type d'application
- une solution robuste, **testée** et éprouvée par sa communauté
- une solution **documentée**
- une solution largement **adoptée** par sa communauté, qui peut donc nous aider (StackOverflow, IRC, ...)


Il existe plusieurs frameworks PHP, nous utiliserons ici [Symfony](https://symfony.com), un framework français suivant l'architecture MVC (Model, View, Controller)


## L'application

Nous allons développer un mini réseau social, permettant aux membres de publier des messages sur un "mur".
Les membres pourront s'ajouter en ami, et la visibilité des posts sur le mur changera en fonction du visiteur. Si le visiteur est ami avec l'auteur, il verra tous les posts, sinon il verra seulement les posts publics.

## Pré-requis

- **php** _version 7 ou plus_, accessible en ligne de commande
- **MySQL** _version 5 ou plus_
- **composer**, accessible en ligne de commande

Vérifier également les pré-requis [ici](https://symfony.com/doc/current/reference/requirements.html).

## Installation du projet

#### En partant de zéro :

Suivre la [documentation officielle](https://symfony.com/doc/current/setup.html)

#### En clonant ce dépôt

```sh
# Cloner le projet dans le dossier <monDossier>
git clone git@gitlab.com:infrep33-web/2019-fullstack/instafrep.git monDossier

# Aller dans le dossier racine du projet
cd monDossier

# Installation des dépendances
composer install 
```

#### Récupérer le projet en cours de route

Si vous avez cloné ce projet puis développé de votre coté, vous souhaiterez peut être vous synchroniser avec l'avancement de la branche `master` officielle (ou une autre branche). Il est possible que d'autres dépendances aient été ajoutées au projet, donc il est bon de prendre le réflexe de faire `composer install` après avoir récupérer le code mis à jour.

> **Bon à savoir :** Faire `composer install` après **chaque** `git pull`, car des dépendances ont pu être ajoutées pendant le développement du projet

## Développement du projet

### Ligne de commande

Symfony fourni un utilitaire en ligne de commande pour effectuer tout un tas d'opérations, et nous allons nous en servir de manière intensive. Il faut donc **avoir un terminal ouvert à la racine du projet** en permanence.

Chaque commande suivra le format suivant : 
```
php bin/console <commande> <options> <arguments>
```

Pour voir la liste des commandes disponibles :
```
php bin/console
```

Pour obtenir de l'aide sur un commande précise :
```
php bin/console <commande> --help
```

### Serveur de développement

Le framework fournit un petit serveur web pour le développement, ce qui est très pratique, car il n'y a pas besoin d'`Apache` (ou `nginx`) pour développer un projet Symfony.

> Il faut tout même un serveur MySQL car nous allons avoir besoin d'une base de données. Si vous utilisez WAMP/MAMP, vous pouvez donc éteindre le service Apache.

Pour démarrer ce serveur de développement :

```sh
# Démarrer le processus du serveur dans le terminal courant
# (ctrl + c pour l'arrêter)

php bin/console server:run
```

ou


```sh
# Démarrer le serveur en arrière plan
php bin/console server:start

# Arreter le serveur en arrière plan
php bin/console server:stop
```
