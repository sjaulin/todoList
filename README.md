# ToDoList

A light todolist tool.

**Code Quality** : <a href="https://codeclimate.com/github/sjaulin/todolist/maintainability"><img src="https://api.codeclimate.com/v1/badges/ea251523a1de60917c97/maintainability" /></a>

## Install Dependencies

### For PHP dependencies
```
composer install
```

### For JS, CSS dependencies
```
npm install
```

### Build CSS & JS
```
npm run-script build
```

## Run containers

```
docker-compose --env-file docker-compose.env up -d
```

## Create database & Init data

Enter in Apache container :

```
docker exec -it todolist_www_1 /bin/bash
```

**In container :**

- create database & tables :

```
php ./bin/console doctrine:database:create
php ./bin/console doctrine:migration:migrate -n
```

- Init. data 
```
php ./bin/console doctrine:fixtures:load -n
```

## Access to application

Url : http://localhost:8080/

Login on admin user : admin / password
Login on simple user : user1 / password
