Documentation technique : La sécurité <!-- omit in toc -->
=====================================

Cette documentation à pour objet : 

- Rappel des notions de base du bundle security qui sont nécessaire de connaitre pour travailler sur cette application.
- Explication de code qui a été mis en place sur cette application.

Sommaire :
- [Introduction](#introduction)
- [Classe user](#classe-user)
  - [CRUD des objets de la classe user](#crud-des-objets-de-la-classe-user)
- [Authentification](#authentification)
  - [Firewall](#firewall)
  - [Authenticator](#authenticator)
  - [Encoders](#encoders)
  - [Provider](#provider)
- [Autorisation](#autorisation)
  - [Autorisation par rôles](#autorisation-par-rôles)
    - [Via "access_control"](#via-access_control)
    - [Via les annotations des classes Controller](#via-les-annotations-des-classes-controller)
  - [Information sur l'utilisateur.](#information-sur-lutilisateur)
  - [Autorisation par voter](#autorisation-par-voter)
    - [Méthode supports()](#méthode-supports)
    - [Méthode voteOnAttribute()](#méthode-voteonattribute)

Introduction
============
La sécurité est couverte à travers deux aspects, l'authentification des utilisateurs et l'autorisation des utilisateurs.
**Fichier de configuration**
La configuration de la sécurité se fait dans le fichier [config\packages\security.yaml](config\packages\security.yaml)

Classe user
===========
Les utilisateurs de l'application sont représentés par la classe [Entity\User.php](src/Entity/User.php) et donc implémente l'interface **UserInterface**

Le stockage des utilisateurs dans la base de données se fait via Doctrine en mappant les champs de la base de données avec les propriétés de la classe [Entity\User.php](src/Entity/User.php)

Prorpiétés utiles : 

- username
- password
- roles : tableau de string contenant les rôles de l'utilisateur

En plus des getter et setter, on y trouve les méthodes de la userInterface pour accéder aux données propres à la sécurité.

Par exemple : getUsername(), getPassword(), getRoles()...

CRUD des objets de la classe user
---------------------------------
Les routes pour le CRUD des objets de la classe Entity\User.php se fait dans la classe [Controller\UserController.php](src/Controller/UserController.php) 

Authentification
================

Sur notre application, l'authentification se fait par le biais d'un [formulaire d'authentification](src/Controller/DefaultController.php)
L'utilisateur doit saisir ses identifiants correspondants aux propriétés username et password de la classe [Entity\User.php](src/Entity/User.php)

Firewall
--------
Dans Symfony, les politiques d'authentification se définissent au travers des firewall (espaces de l'application).
Chaque firewall est délimité par un groupe d'URL définit par la clé **pattern**
Pour chaque requête, un seul firewall est actif.

Nous utilisons le firewall par défaut **main** qui est utilisé par toutes les autres URL (non définies par le pattern des autres firewall)

Fichier [config\packages\security.yaml](config\packages\security.yaml) expliqué : 
```yaml
security:
  # ...
  firewalls:
    # ...
      main:
        anonymous: true # L'accès est possible aux visiteurs non authentifiés.
        provider: app_user_provider # les données des utilisateurs se trouvent dans le provider app_user_provider
        form_login: # Pour ce firewall, on utilise un formulaire d'authentification
          login_path: homepage # Où se trouve le formulaire
          check_path: homepage # Sur quelle url sont envoyé les informations de connection
          csrf_token_generator: security.csrf.token_manager
        logout:
          path: app_logout
```
Authenticator
-------------

Un authenticator est une classe qui permet de définir la façon dont les utilisateurs doivent s'authentifier à l'application.
Dans notre cas, nous n'avons pas besoin d'authenticator, nous utilisons donc simplement le form_login fournit par Symfony : [form_login](https://symfony.com/doc/current/security/form_login.html) 

Encoders
--------
La clé encoders permet de définir l'algorithme d'encodage utilisé en fonction des classes sur lesquels il s'applique.

Dans notre fichier nous avons : 
```yaml
security:
  encoders:
    App\Entity\User:
      algorithm: auto
```
Ce qui veut dire que lorsque nous encodons un mot de passe de la classe User, nous laissons Symfony choisir le meilleur encodeur disponible sur la machine qui héberge l'application.

Provider
--------

Les providers indiquent au composant Security où se trouvent les données des utilisateurs.
Dans notre application, la seule source d'utilisateur se trouve dans la base de données.

Donc, dans le fichier security.yaml nous n'avons qu'un seul provider : 
```yaml
  providers:
    app_user_provider: # nom du provider
      entity: # Type de provider : entité
        class: App\Entity\User # Nom de la classe d'entité
        property: username # on retrouvera cet utilisateur par son username.
```
Ceci veut dire que notre "fournisseur" d'utilisateur est de type entité, que l'entité est App\Entity\User et qu'on retrouvera cet utilisateur par son username.

Dans le cas d'une authentification via active directory, il sera nécessaire de créer un autre provider pour cela.

Autorisation
============

Une fois authentifié, l'accès aux différentes routes de l'application se fait de plusieurs manière :

Autorisation par rôles
----------------------

La création des utilisateurs est réservé aux utilisateurs avec les rôle **ROLE_ADMIN**

Comme vu plus haut, les utilisateurs peuvent avoir des rôles spécifiques définits dans la propriété **$roles**.
Les utilisateurs ont au moins un rôle par défaut : **ROLE_USER**

### Via "access_control"
Pour réserver l'accès à certaines roles utilisateurs, peut se faire dans le fichier [config\packages\security.yaml](config\packages\security.yaml), ce n'est pas cette solution qui est utilisé.

Exemple : 

```yaml
  access_control:
    # - { path: ^/admin, roles: ROLE_ADMIN }
    # - { path: ^/profile, roles: ROLE_USER }
```

### Via les annotations des classes Controller

Accès réservé à toutes les routes d'une classe : 

```php
/**
 * @Route("/users", name="user_list")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
```

Accès réservé à une route en particulié :
```php
/**
  * @Route("/users", name="user_list")
  * @IsGranted("ROLE_ADMIN")
  */
```

Information sur l'utilisateur.
------------------------------

**Pour obtenir le rôle d'un utilisateur**

Depuis d'entité user : 

```php
$user->getRoles();
```

Depuis un controller (qui étend de la classe AbstractController) :

```php
$this->getUser()->getRoles();
```

**Pour vérifier qu'un utilisateur à un certain rôle**

Depuis un controller (qui étend de la classe AbstractController) : 

```php
if ($this->isGranted('ROLE_ADMIN')) {
    // instructions
}
```

Autorisation par voter
----------------------

Dans notre application, le simple fait de restreindre une route pour un rôle n'est pas suffisant.
Pour restrenidre l'accès à la modification des tâches seulement aux auteurs, nous avons utilisé un voter.

A chaque fois que l'on utilise la méthode $security->isgranted() les voters sont appelés, il peut y avoir plusieurs voter.

Voici notre premier voter, la classe [TaskVoter.php](src/Security/Voter/TaskVoter.php)
Cette classe est enfant de la classe abstraite **Voter** et donc, ces 2 méthodes abstraites sont re-définies :

### Méthode supports()
A chaque utilisation de **$security->isgranted('droit', $object)**, la méthode **supports()** de tous les voters est appelée pour savoir si CE voter doit prendre en charge l'autorisation.
Si le voter prend en charge l'autorisation, il doit retourner **TRUE**

```php
protected function supports($attribute, $subject) {
  // retourne TRUE si le voter prend en charge l'autorisation.
  // $attribute => droit demandé par la fonction isgranted()
  // $sujet => objet passé à la fonction isgranted()
}
```

### Méthode voteOnAttribute()
Retourne TRUE si l'utilisateur a le droit et donc $security->isgranted() retournera TRUE

```php
protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
   // Retourne TRUE si l'utilisateur a le droit
   // $attribute => droit demandé par la fonction isgranted()
   // $sujet => objet passé à la fonction isgranted()
}
```
