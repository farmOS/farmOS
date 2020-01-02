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
    networks:
      farmos-dev-net:

  farmos:
    depends_on:
      - db
    image: farmos/farmos:7.x-1.2
    volumes:
      - './sites:/var/www/html/sites'
    expose:
      - '80'
    environment:
      FARMOS_DEV: 'true'
    networks:
      farmos-dev-net:

  proxy:
    depends_on:
      - farmos
    image: nginx:latest
    volumes:
      - './nginx.conf:/etc/nginx/nginx.conf'
      - './nginx/error_logs:/etc/nginx/error_logs'
      - './devcerts:/etc/nginx/certs'
    ports:
      - 80:80
      - 443:443
    networks:
      farmos-dev-net:

networks:
  farmos-dev-net:
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
      proxy_pass http://farmos:80;

      # Allow logins through farmos.app
      if ($http_origin ~ '^https://(.*\.)?farmos\.app(:[0-9]+)?$') {
        add_header 'Access-Control-Allow-Origin' $http_origin;
        add_header 'Access-Control-Allow-Credentials' 'true';
      }

      # proxy_redirect off;
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

Additions to **sites/default/settings.php (simplified from [this SO answer](https://drupal.stackexchange.com/a/257399))**

```php
$conf['reverse_proxy'] = TRUE;
$conf['reverse_proxy_addresses'] = [@$_SERVER['REMOTE_ADDR']];
$base_url = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER['SERVER_NAME'];
```

**Certificates generated with [mkcert]**

```sh
mkdir devcerts && mkcert -key-file devcerts/key.pem -cert-file devcerts/cert.pem farmos.local *.farmos.local
```

**Fake domain added to /etc/hosts**

```sh
echo "127.0.0.1 farmos.local" >> /etc/hosts
```

This yields a FarmOS installation which can be accessed via https://farmos.local

![image](https://user-images.githubusercontent.com/30754460/71647994-35b45400-2cb3-11ea-8702-b44c2fcebe66.png)

[Developing farmOS with Docker]: /development/docker
[NGINX]: https://www.nginx.com/
[mkcert]: https://github.com/FiloSottile/mkcert

