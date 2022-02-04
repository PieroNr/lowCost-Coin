# lowCost-Coin
Mise en œuvre du cours de symfony doctrine


# Requirements 
```
- php ^8.1.1
- symfony cli
```

## Clone this repository

`git clone https://github.com/PieroNr/lowCost-Coin`

Go inside 

`cd lowCost-Coin`

Update composer

`composer install`

Create docker container

`docker-compose up -d`


Load fixtures:

`symfony console doctrine:fixtures:load --no-interaction`

Run symfony server

`symfony serve`

## Tips

Admin logs in-app

```
email: admin@admin.fr
password: admin
```

Promote user to admin role

`symfony console app:user:promote user@email.com ROLE_ADMIN`
