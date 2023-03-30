# Local HTTPS

Some development testing is easier with farmOS on an `https://` endpoint.
A separate [Nginx](https://nginx.com) reverse proxy provides a simple way to
achieve this without any changes to the Apache configuration that runs in the
farmOS Docker container.

First, generate self-signed SSL certificate files into an `ssl` directory,
from the directory that your `docker-compose.yml` file is in:

```
mkdir ssl
openssl req -newkey rsa:4096 -x509 -sha256 -nodes -out ssl/openssl.crt -keyout ssl/openssl.key
```

Create a file called `nginx.conf` alongside `docker-compose.yml`:

```
events {}
http {
  server {
      listen 80 default_server;
      listen [::]:80 default_server;
      server_name _;
      return 301 https://$host$request_uri;
  }
  server {
    server_name localhost;
    listen 443 ssl;
    ssl_certificate /etc/nginx/ssl/openssl.crt;
    ssl_certificate_key /etc/nginx/ssl/openssl.key;
    location / {
      proxy_set_header Host $http_host;
      proxy_set_header X-Real-IP $remote_addr;
      proxy_set_header X-Forwarded-Host $http_host;
      proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header X-Forwarded-Proto $scheme;
      proxy_buffer_size 128k;
      proxy_buffers 4 256k;
      proxy_busy_buffers_size 256k;
      proxy_pass http://www;
    }
  }
}
```

Add the following lines to `www/web/sites/default/settings.php`:

```
$settings['reverse_proxy'] = TRUE;
$settings['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];
$settings['reverse_proxy_trusted_headers'] = \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_ALL;
```

Add the following service to your local `docker-compose.yml` file:

```
  proxy:
    image: nginx
    depends_on:
      - www
    ports:
      - '80:80'
      - '443:443'
    volumes:
      - './nginx.conf:/etc/nginx/nginx.conf'
      - './ssl:/etc/nginx/ssl'
```

Also remove port 80 from the `www` service:

```
    ports:
      - '80:80'
```

Finally, start the Docker services:

`docker compose up`

farmOS is now accessible via `https://localhost`.
