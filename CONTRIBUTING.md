Documentation technique : Nouveau développeur <!-- omit in toc -->
=============================================

Cette documentation à pour objet : 

- Aider les développeurs à installer leur poste de développement
- Expliquer les règles de qualité du code à adopté sur le projet

Sommaire :
- [Installation du poste de développeur](#installation-du-poste-de-développeur)
  - [Visual Studio Code](#visual-studio-code)
- [Qualité de code](#qualité-de-code)
  - [Tests](#tests)
  - [Respect des standards](#respect-des-standards)
    - [PHPCS & PSR](#phpcs--psr)
    - [PSPStan](#pspstan)
  - [Intégration continue](#intégration-continue)

Installation du poste de développeur
====================================

Visual Studio Code
------------------

Qualité de code
===============

Tests
-----

Nous effectuons des tests unitaires et fonctionels avec PHPUNIT.
Nous imposons une couverture de test d'au moins 70% du code (indicateur : Lines).
Les tests sont à écrire dans le dossier ./tests

**Pour exécuter tous les tests**

- option --testdox : affiche l'état d'avancement des tests
- option --coverage-text : affiche un rapport de couverture de code

Dans tous les cas, un rapport HTML de la couverture de code sera généré dans le dossier [phpunit-report/coverage/html](phpunit-report/coverage/html/index.html)

```shell
./vendor/bin/simple-phpunit --testdox --coverage-text 
```

**Pour exécuter seulement les tests d'une classe.**
Exemple : On veut exécuter les tests de la classe TaskControllerTest :

```shell
./vendor/bin/simple-phpunit --testdox .\tests\Controller\TaskControllerTest.php
```

**Pour exécuter seulement UNE méthode les tests d'une classe**

Exemple : On veut exécuter le test testAccessDenied de la classe TaskControllerTest :
```
./vendor/bin/simple-phpunit .\tests\Controller\TaskControllerTest.php --filter testAccessDenied 
```

Respect des standards
---------------------

### PHPCS & PSR

### PSPStan

Intégration continue
--------------------

A chaque push ou merge sur la branche **dev**, le code est intégré au serveur d'intégration via **travis** qui éxecutera un scénario pour vérifier la qualité du code selon les règles configurées.

Le scénario d'intégration est configuré dans le fichier [travis.yml](.travis.yml)