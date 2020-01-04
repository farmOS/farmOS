# Configuring a Local Https Reverse Proxy

For general information about development setup first see [Developing farmOS with Docker].

Some development testing is easier with FarmOS behind an https endpoint. A separate [NGINX] reverse proxy provides a simple way to configure an https endpoint without any changes to the Apache2 configuration which FarmOS/Drual is running on.

## Example Configuration

**docker-compose.yml**

```yaml
version: '2'
services:
  db:
    image: mariadb:latest
    volumes:
      - './db:/var/lib/mysql'
    expose:
      - '3306'
    environment:
      MYSQL_ROOT_PASSWORD: farm
      MYSQL_DATABASE: farm
      MYSQL_USER: farm
      MYSQL_PASSWORD: farm

  www:
    depends_on:
      - db
    image: farmos/farmos:dev
    volumes:
      - './www:/var/www/html'
    expose:
      - '80'
    environment:
      XDEBUG_CONFIG: remote_host=172.17.0.1

  proxy:
    depends_on:
      - www
    image: nginx:latest
    volumes:
      - './nginx.conf:/etc/nginx/nginx.conf'
      - './nginx/error_logs:/etc/nginx/error_logs'
      - './devcerts:/etc/nginx/certs'
    ports:
      - '80:80'
      - '443:443'

```

**nginx.conf**

```
events {

}

http {
  error_log /etc/nginx/error_logs/error_log.log warn;
  client_max_body_size 20m;

  server {
      listen 80;
      server_name farmos.local;

      rewrite ^/(.*)$ https://$host$request_uri? permanent; 
  }

  server {
    server_name farmos.local;

    location / {
      proxy_pass http://www:80;

      proxy_set_header Host $host;
      proxy_set_header X-Real-IP $remote_addr;
      proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header X-Forwarded-Host $host:443;
      proxy_set_header X-Forwarded-Port 443;
      proxy_set_header X-Forwarded-Server $host;
      proxy_set_header X-Forwarded-Proto https;
    }

    listen 443 ssl;
    ssl_certificate /etc/nginx/certs/cert.pem;
    ssl_certificate_key /etc/nginx/certs/key.pem;
  }
}

```

**Generate certificates with [mkcert]**

```sh
mkdir devcerts && mkcert -key-file devcerts/key.pem -cert-file devcerts/cert.pem farmos.local *.farmos.local
```

**Start containers**

```sh
docker-compose up
```

**Add fake domain to /etc/hosts**

```sh
echo "127.0.0.1 farmos.local" >> /etc/hosts
```

```sh
alias drush="docker-compose exec www drush"
drush site-install farm --locale=us --db-url=mysql://farm:farm@db/farm --site-name=Test0 --account-name=root --account-pass=test install_configure_form.update_status_module='array(FALSE,FALSE)'
```

*Note: It is advisable to update the command above with a better password than 'test' before running it.*

**Enable reverse proxy settings in sites/default/settings.php (simplified from [this SO answer](https://drupal.stackexchange.com/a/257399))**

```sh
sudo sh -c "printf \"\n\n\\\$conf['reverse_proxy'] = TRUE;\n\\\$conf['reverse_proxy_addresses'] = [@\\\$_SERVER['REMOTE_ADDR']];\n\\\$base_url = \\\$_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . \\\$_SERVER['SERVER_NAME'];\n\" >> www/sites/default/settings.php"
```

This yields a FarmOS installation which can be accessed via https://farmos.local with a user named 'root' and a password of 'test' - or the better password that you wisely substituted above.

![image](https://user-images.githubusercontent.com/30754460/71647994-35b45400-2cb3-11ea-8702-b44c2fcebe66.png)

[Developing farmOS with Docker]: /development/docker
[NGINX]: https://www.nginx.com/
[mkcert]: https://github.com/FiloSottile/mkcert

