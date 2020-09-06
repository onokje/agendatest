## Installation
- run `composer install`
- install the symfony cli to use as a webserver: `wget https://get.symfony.com/cli/installer -O - | bash`
  - or setup a webserver with nginx or apache. Refer to symfony docs for more info. 

## Database
- setup your database of choice. Default is to use sqlite. See below.
- execute the database migrations by running `php bin/console d:m:m`
- load database fixtures by running: `php bin/console doctrine:fixtures:load`

#### sqlite setup
- install sqlite driver to use sqlite (on ubuntu/debian): `sudo apt install php7.4-sqlite3`

#### mysql setup
- update the `.env` file and change the `DATABASE_URL` like so: `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"` 

## Running the app
- run symfony `symfony server:start`
- open browser at `http://localhost:8000`
