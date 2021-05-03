# ToDoList v2

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
