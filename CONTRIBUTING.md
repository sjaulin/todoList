CONTRIBUTING <!-- omit in toc -->
============

Cette documentation à pour objet : 

- Aider les développeurs à installer leur poste de développement
- Expliquer les règles de qualité du code à adopter sur le projet
- Expliquer le workflow de contribution à adopter sur le projet

**Sommaire :**
- [Installation du poste de développeur](#installation-du-poste-de-développeur)
  - [Docker](#docker)
  - [Visual Studio Code](#visual-studio-code)
    - [Installation](#installation)
    - [Extensions à installer](#extensions-à-installer)
    - [Configurer VS Code](#configurer-vs-code)
    - [Configurer Xdebug](#configurer-xdebug)
  - [PHP](#php)
  - [Composer](#composer)
  - [NodeJS et NPM](#nodejs-et-npm)
    - [Compiler les fichiers JS, SCSS](#compiler-les-fichiers-js-scss)
  - [GIT](#git)
  - [Symfony CLI](#symfony-cli)
  - [Démarrer l'application](#démarrer-lapplication)
- [Règles de qualité de code](#règles-de-qualité-de-code)
  - [PSR](#psr)
  - [Outils de vérification](#outils-de-vérification)
    - [PHPCS](#phpcs)
    - [PSPStan](#pspstan)
    - [Lint : Twig](#lint--twig)
    - [Revue de code automatisée](#revue-de-code-automatisée)
  - [Intégration continue](#intégration-continue)
  - [Pre-commit](#pre-commit)
  - [Tests unitaires et fonctionnels](#tests-unitaires-et-fonctionnels)
  - [Tests de performances](#tests-de-performances)
- [Workflow de contribution](#workflow-de-contribution)
    - [Travailler sur une fonctionnalité](#travailler-sur-une-fonctionnalité)
    - [Publier la fonctionnalité / récupérer les modifications](#publier-la-fonctionnalité--récupérer-les-modifications)
    - [Merger son code sur develop](#merger-son-code-sur-develop)

Installation du poste de développeur
====================================

Docker
-------

Installez Docker Desktop :
https://docs.docker.com/get-docker/


Visual Studio Code
------------------

### Installation

Télécharger et installer la dernière version du logiciel : https://code.visualstudio.com/

### Extensions à installer

**Docker**
Makes it easy to create, manage, and debug containerized applications.
https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker

**PHP Intelephense**
Améliore l'expérience de développement PHP
https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client

**PHP Namespace Resolver**
Permet d'importer et l'espace de nom des classes que l'on utilise.
https://marketplace.visualstudio.com/items?itemName=MehediDracula.php-namespace-resolver

**PHP Debug**
Debug support for PHP with Xdebug
https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug

**Twig Language 2**
Autocompletion pour fichier Twig
https://marketplace.visualstudio.com/items?itemName=mblode.twig-language-2

**PHP CS Fixer**
PHP CS Fixer extension for VS Code, php formatter, php code beautify tool, format html
https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer

**Git graph**
View a Git Graph of your repository, and perform Git actions from the graph.
https://marketplace.visualstudio.com/items?itemName=mhutchie.git-graph

**PlantUml**
Permet de coder des diagrammes UML
https://marketplace.visualstudio.com/items?itemName=jebbs.plantuml

### Configurer VS Code
Une fois les extensions installés, ouvrir les paramètres de VS Code.
Sur Windows : ```CTRL + ;```
Puis, appliquer les paramètres suivants : 

**Limiter les suggestions d'autocomplétion**
```
"php.suggest.basic": false
```

**Pour utiliser Emmet dans un fichier twig**
```
"emmet.includeLanguages": {
  "twig": "html",
  "javascript": "javascriptreact"
}
```

**Activer le formatage à l'enregistrement des fichiers**
```
"editor.formatOnPaste": true
```

### Configurer Xdebug
Dans dossier .vscode, créez un fichier .launch.json avec ceci :
```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug",
            "type": "php",
            "request": "launch",
            "port": 9000
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 9000
        }
    ]
}
```

PHP
---

Le site peut être lancé avec PHP, Apache et Mysql à parir d'un conteneur Docker (voir section sur Docker) mais, pour exécuter les commandes de composer et de la console de Symfony, il est nécessaire d'installer PHP en local.

- Télécharger le fichier : http://www.php.net/downloads.php
- Extraire le fichier dans c:\php
- Copier le fichier C:\php\php.ini-development dans C:\php\php.ini
- Définir le dossier où sont stockées les extensions :

```
extension_dir = "C:/php/ext"
```

- Activer les extensions :
```
  extension=curl
  extension=gd2
  extension=mbstring
  extension=mysql
  extension=pdo_mysql
  extension=xmlrpc
  extension=openssl
```

- Ajouter C:\php à la variable PATH 

- Pour vérifier que php est installé :

```php -v```

Pour voir la configuration du PHP et notamment pour vérifier l'emplacement du PHP
```php -i```

Puis rechercher "Loaded Configuration"
Exemple : Loaded Configuration File => C:\php\php.ini

Composer
--------

**Sous Windows**

Télécharger et installer le fichier Composer-Setup.exe¨à cette adresse :
https://getcomposer.org/download/

Ceci installe la dernière version de composer et l'ajoute le chemin dans la variable ```PATH``` dans les variables d'environnement Windows.

Pour voir quelle version de composer est installé :
```composer -V```

NodeJS et NPM
--------------

Pour développer des applications modernes en javascript (notamment pour compiler et optimiser notre code), on utilise un environnement de développement nécessitant NodeJS et NPM, vous pouvez l'installer à partir d'ici : https://nodejs.org/en/

**Ceci install**

- Le Runtime
- Le gestionnaire de paquet npm
- Un lien vers la doc
- Ajout du PATH windows

### Compiler les fichiers JS, SCSS

Pour compiler les fichiers
```
npm run-script build
```

GIT
----

Télécharger et installer GIT : https://git-scm.com/
Suivre les instructions.

Symfony CLI
-----------

La commande ```symfony``` est une alternative à la commande ```php .\bin\console```
Cette console améliorée inclus : 
- Une meilleur solution pour créer des applications Symfony
- Une amélioration du serveur web local de php
- Un outils pour vérifier les vulnérabilités de sécurité.

Télécharger et installer la console Symfony : https://symfony.com/download


Démarrer l'application
----------------------

Pour démarrer l'application en local via Docker, se référer au document [README.md](./README.md)

Règles de qualité de code
==========================

PSR
----
La norme de codage que nous devons adopter est PSR12
Voir les spécifications : https://www.php-fig.org/psr/psr-12/

Outils de vérification
----------------------

### PHPCS

PHP Code Sniffer est une commande qui permet de détecter dans le code des violations éventuelles des standards de programmation que nous avons définis (PSR12)

Pour lancer une analyse :
```./vendor/bin/phpcs```

### PSPStan

PHPStan est un outil d'analyse statique. Il détecte les problèmes de structure dans le code qui peuvent conduire à des bugs.
Nous devons passer au moins le niveau 6 pour passer l'intégration continue.

Pour lancer une analyse :
```./vendor/bin/phpstan analyse src tests```

### Lint : Twig

Cette commande vérifie les erreurs de syntaxe dans es fichiers twig.

Pour lancer une analyse : 

```
php bin/console lint:twig ./templates/
```

### Revue de code automatisée
La revue de code automatisée permet de d'automatiser un certain nombre de vérifications liées à la sécurité et aux bonnes pratiques de programmation.
Pour cela, nous utilisons Code Climate qui fera ses vérifications et attribuera une note qui sera mis à jour à chaque modification sur la branche main.

https://codeclimate.com/github/sjaulin/todolist

Il est important de veiller à rester sur une note de A ou B.

Intégration continue
--------------------

A chaque push ou merge sur la branche **develop**, le code est intégré au serveur d'intégration via **travis** qui éxecutera un scénario pour vérifier la qualité du code selon les règles configurées.
Le scénario d'intégration est configuré dans le fichier [travis.yml](.travis.yml)

Pour voir les builds : https://www.travis-ci.com/

Avant chaque commit, il est donc important de lancer ces mêmes commandes et vérifier qu'il n'y a pas d'erreur, pour cela nous recommandons l'utilisation d'un fichier pre-commit

Pre-commit
----------

Pour lancer les commandes de vérification de la qualité de code avant chaque commit, ajouter un fichier ```pre-commit``` dans le dossier ```.git\hooks``` du projet.
Le conteneu du fichier :

```sh
#!/bin/sh

echo "phpcs..."
./vendor/bin/phpcs

echo "phpstan..."
./vendor/bin/phpstan analyse

echo "lint:twig"
php bin/console lint:twig ./templates/

exit $?
```

Pour exécuter le fichier et lancer toutes les commandes sans avoir à faire un commit, lancez la commande :

```
bash .git/hooks/pre-commit
```

Tests unitaires et fonctionnels
-------------------------------

Les tests PHPUNIT sont à écrire dans le dossier ./tests
Il est important de s'assurer d'une couverture de test d'au moins 70%  (indicateur : Lines).

Pour lancer les tests

```./vendor/bin/simple-phpunit --testdox --coverage-text```

ou pour lancerles tests avec écriture d'un fichier de log (utilile en cas d'erreur)

```./vendor/bin/simple-phpunit --testdox --coverage-text > phpunit-report/log.txt```

**Pour exécuter seulement les tests d'une classe.**
Exemple : On veut exécuter les tests de la classe TaskControllerTest :

```./vendor/bin/simple-phpunit --testdox .\tests\Controller\TaskControllerTest.php```

**Pour exécuter seulement UNE méthode les tests d'une classe**

Exemple : On veut exécuter le test testAccessDenied de la classe TaskControllerTest :
```
./vendor/bin/simple-phpunit .\tests\Controller\TaskControllerTest.php --filter testAccessDenied 
```

Tests de performances
----------------------

L'outil d'analyse de performance [Blackfire.io](https://blackfire.io/) est installé sur l'environnement de développement.
Pour lancer une analyse, lancer la commande suivante :

Pour analyser la page d'accueil :
```
docker-compose exec blackfire blackfire curl http://www
```

Pour analyser la liste des tâche à faire :
```
docker-compose exec blackfire blackfire curl http://www/tasks/list/0
```

Une fois l'analyse effectuée, un lien s'affiche dans la console qui permet d'accéder aux résulktats de l'analyse.

Workflow de contribution
========================

Pour notre workflow de contribution, nous utilisons en partie les commandes  git-flow.
Pour initialisé git-flow :
```git flow init```

### Travailler sur une fonctionnalité

1- Pour commencer une fonctionnalité :
```git flow feature start feature/1```

Cette commande crée une nouvelle branche de fonctionnalité basée sur 'develop' et passe sur cette branche

2- Apporter vos modifications et faire le commit
```
git add monfichier.php
git commit -m "feature/1 Bla bla bla"
```

3- Terminer la fonctionnalité
```git flow feature finish feature/1```

### Publier la fonctionnalité / récupérer les modifications
Pour publier la fonctionnalité afin de la partager avec les autres développeurs :
```git flow feature publish feature/1```

Inversement, pour récupérer les éventuels modifications de la feature des autres développeurs :
```git pull origin feature/1```

### Merger son code sur develop

Pour Merger les développements dans la branche develop, il est nécessaire de faire une pull request.

Sur Github, sur la liste des branches, cliquez sur le bouton **New pull Request** en face de la branche de la feature en quiestion.

Une fois le processus d'intégration continue et après validation du lead développeur, le code sera appliqué à la branche develop.
