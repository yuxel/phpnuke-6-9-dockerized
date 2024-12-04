# PHP-Nuke 9.6 Docker file

Inherited from - https://github.com/rodvlopes/php4-mysql4-apache2.2-docker 


# Run with compose

    docker compose up --build

# Install script


    docker exec `docker ps -q --filter name=php4-mysql4-apache22`  bash /tmp/script/install.sh 

# Post install

Visit http://localhost/admin.php
