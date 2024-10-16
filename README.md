# IIS_PROJECT 2024

## Useful:
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
