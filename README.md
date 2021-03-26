# ToDoList v2

## Run Docker containers

```
docker-compose --env-file docker-compose.env up -d
```

## Run PHPUnit Test

**With testdox + report :**

```
./vendor/bin/simple-phpunit --testdox --coverage-text
```

**With testdox + report + log:**

```
./vendor/bin/simple-phpunit --testdox --coverage-text > phpunit-report/log.txt
```
