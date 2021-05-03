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
  - [PHPCS](#phpcs)
  - [GIT](#git)
  - [Symfony CLI](#symfony-cli)
  - [Démarrer l'application](#démarrer-lapplication)
- [Règles de qualité de code](#règles-de-qualité-de-code)
  - [Intégration continue](#intégration-continue)
  - [Tests unitaires et fonctionnels](#tests-unitaires-et-fonctionnels)
  - [PSR](#psr)
  - [PHPCS](#phpcs-1)
  - [PSPStan](#pspstan)
- [Workflow de contribution](#workflow-de-contribution)
    - [Branche de la feature](#branche-de-la-feature)
    - [Commit message](#commit-message)
    - [Merge request sur dev](#merge-request-sur-dev)
      - [Intégration continue](#intégration-continue-1)

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

PHPCS
------

Installation de phpcs va composer :

```composer global require squizlabs/php_codesniffer```

Pour vérifier que phpcs est installé :

```phpcs -i```


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

Intégration continue
--------------------

A chaque push ou merge sur la branche **dev**, le code est intégré au serveur d'intégration via **travis** qui éxecutera un scénario pour vérifier la qualité du code selon les règles configurées.
Le scénario d'intégration est configuré dans le fichier [travis.yml](.travis.yml)

Pour voir les builds : https://www.travis-ci.com/

Avant chaque commit, il est donc important de lancer ces mêmes commandes et vérifier qu'il n'y a pas d'erreur.

Tests unitaires et fonctionnels
-------------------------------

Les tests PHPUNIT sont à écrire dans le dossier ./tests
Il est important de s'assurer d'une couverture de test d'au moins 70%  (indicateur : Lines).

```./vendor/bin/simple-phpunit --testdox --coverage-text```

ou

```./vendor/bin/simple-phpunit --testdox --coverage-text > phpunit-report/log.txt```

**Pour exécuter seulement les tests d'une classe.**
Exemple : On veut exécuter les tests de la classe TaskControllerTest :

```./vendor/bin/simple-phpunit --testdox .\tests\Controller\TaskControllerTest.php```

**Pour exécuter seulement UNE méthode les tests d'une classe**

Exemple : On veut exécuter le test testAccessDenied de la classe TaskControllerTest :
```
./vendor/bin/simple-phpunit .\tests\Controller\TaskControllerTest.php --filter testAccessDenied 
```

PSR
----
La norme de codage que nous devons adopter est PSR12
Voir les spécifications : https://www.php-fig.org/psr/psr-12/

PHPCS
-----

PHP Code Sniffer est une commande qui permet de détecter dans le code des violations éventuelles des standards de programmation que nous avons définis (PSR12)

Pour lancer une analyse :
```./vendor/bin/phpcs```

PSPStan
-------
PHPStan est un outil d'analyse statique. Il détecte les problèmes de structure dans le code qui peuvent conduire à des bugs.

Pour lancer une analyse :
```./vendor/bin/phpstan analyse src tests```

Workflow de contribution
========================

1- Créer une branche en local numérotée avec le numéro de l'issue correspondante.

```
git fetch && git pull
git checkout -b feature-1 dev
```

### Branche de la feature
### Commit message
Voir modèle Angular

### Merge request sur dev

#### Intégration continue

