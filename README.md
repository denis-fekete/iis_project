# IIS_PROJECT 2024

## Useful:
### Installation
1. Clone from git
2. Check if you have installed docker, if not: https://docs.docker.com/desktop/install/linux/
3. Run `docker_build.sh` (probably will start the sail as well)
4. From now on use `sail.sh up` or add sail to aliases and use `sail up`

### Implemented functionality
- Authentication works, you can create account, you will be logged in and you will see Profile.
- You can log in as Admin *( using email: admin@admin.com and password:admin )* but you will need to run sail db:seed for seeders to create admin account, or create it yourself in myphpadmin

### sail.sh
- script for running Laravel in sail docker
- I used aliases for easier manipulation: `alias sail="{PATH_TO_PROJECT}/iis_project/sail.sh"`

if sail is not working run [`newgrp docker`](https://docs.docker.com/engine/install/linux-postinstall/)

### phpmyadmin
- [`http://localhost:8080/`](http://localhost:8080/)
```
server: mysql
user: sail
password: password
```
*change in .env*
