# IIS Project 2024/2025 - Conferences

## About
Information system for managing conferences, lectures, reservations and users.  

## Technologies used and Framework versions that were tested
- PHP version 8.1
- Composer 2.2.0
- Laravel version 10.10
- npm version 10.8.2
- other frameworks and dependencies are located in composer.json and are managed by composer

## Installation with docker
1. Clone git repository
2. Update node modules
```
$ npm install
```
3. Run `docker_build.sh` script
```
$ ./docker_build.sh
```
4. Generate artisan key
```
$ ./vendor/bin/sail artisan key:generate
```
or use `sail.sh` script for easier use
```
$ ./sail.sh artisan key:generate
```
5. Set-up `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` and `APP_BASE_URL` in .env
6. Start website with `./sail.sh up`
