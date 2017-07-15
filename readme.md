# MediaResa
## Requirements
- PHP >= 5.4
- PostgreSQL >= 9.0
- Npm
- Composer
- Bower (formajax dependency is a GIT repository)
- [libxrender1](https://packages.debian.org/jessie/libxrender1)
- [libxext6](https://packages.debian.org/jessie/libxext6)

## Installation
- Clone the repository : `git clone git@gitlab.escaledigitale.com:mediaresa/mediaresa-app.git`
- Install PHP dependencies : `composer install`
- Install node modules : `npm install`
- Install JS dependencies : `bower install`
- Generate builds : `grunt prod`
- Copy .env.example file and rename it to .env : `cp .env.example ./.env`
- Update information in the `.env` file :

```
Test keys

MANDRILL_KEY=jOJd_nEPhLGLyAOpeZ64fQ

STRIPE_SECRET_KEY=sk_test_OXTyO2unNa7yvoUJZO1ogNbY
STRIPE_PUBLIC_KEY=pk_test_ND10aIJL9sXo5nzbLmO6Kpm7
```

- Generate APP_KEY : `php artisan key:generate`
- Set new password to DB_PASSWORD
- Migrate and seed : `php artisan migrate --seed`

## Shared folders / files
- .env
- public/img/

## Local update
- git pull
- If the composer.lock file has been updated : `composer install --no-dev`
- If a new migration has been created (see database/migrations/) : `[php] artisan migrate`


## Migrations
- You may need to be connected to your Homestead machine : `homestead ssh`
- To migrate : `php artisan migrate`
- To rollback migration : `php artisan migrate:rollback`

## Fake seeding
- To reload fake data you can type `php artisan db:seed`
- To reload only one faker : `php artisan db:seed --class=MyClassSeederName`

## Access to preprod
- Edit your `/etc/hosts`: `sudo nano /etc/hosts`
- Add this line (or just domains) : 91.121.35.85 admin.preprod.mediaresa.fr www.preprod.mediaresa.fr
- Save and quit

# Backup
## Linux
- To backup the database : `` pg_dump [DB_NAME] -h [DB_HOST] --clean -U [DB_USER] --password > "mediaresa-`date '+%Y-%m-%d'`.sql" ``
- To backup specific table with insert statements (rather than copy) : ``pg_dump --inserts --table=[TABLE_NAME] [DB_NAME] -h [DB_HOST] --clean -U homestead --password > "mediaresa-[TABLE_NAME]-`date '+%Y-%m-%d'`.sql"``
- To restore the database : `` psql [DB_NAME] -h [DB_HOST] -U [DB_USER] --password -f [BACKUP_FILE] ``

### Windows
First, you need to go to your PSQL installation folder.

- To restore the database : `` psql -f [BACKUP_FILE] [DB_NAME] [DB_USER] ``
