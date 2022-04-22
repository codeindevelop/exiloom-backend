### Exiloom Exchange BackEnd

Exiloom is a Crypto currency exchange based in iran .

## How Can Start

<br>
first and first you most be install Docker and Docker Compose on your own machine because exiloom DataBase and some DB applications most be hosted on Docker Containers for saftiy and best manage with isolation location .

after clone go to the project folder and run `composer install` for install all requirment project package.

after for install Requirements you most be run DataBase Services by type `docker-compose up -d` for pull and install Docker Requirment images for this project .

after that you can access to DataBase with environment by create `.evn` file on `.env.example` file .

## Installing PHP 8.0 with Nginx For Use Docker
<br>
Nginx does’t have built-in support for processing PHP files. We’ll use PHP-FPM (“fastCGI process manager”) to handle the PHP files.

Run the following commands to install PHP and PHP FPM packages:
<br>
<br>
```
sudo apt update
sudo add-apt-repository ppa:ondrej/php 
sudo apt install php8.0-fpm
sudo apt install openssl php-common php-curl php-json php-mbstring php-mysql php-xml php-zip
```
Once the installation is completed, the FPM service will start automatically. To check the status of the service, run
<br>
<br>

```
systemctl status php8.0-fpm
```

```nginx
● php8.0-fpm.service - The PHP 8.0 FastCGI Process Manager
     Loaded: loaded (/lib/systemd/system/php8.0-fpm.service; enabled; vendor preset: enabled)
     Active: active (running) since Thu 2020-12-03 16:10:47 UTC; 6s ago

```

You can now edit the Nginx server block and add the following lines so that Nginx can process PHP files in the blow Nginx Config
<br>
<br>

## Nginx Config

<br>
for config nginx server you most be create `exiloom-api` file in `/etc/nginx/sites-avalible` folder after that paste below codes in `exiloom-api` file

<br>

```nginx

server {
    listen 80;
    server_name api.exiloom.com;
    root /var/www/exiloom/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

```

<br>

if you want use SSL on server and you want use CloudFlare End-To-End Full Encryption you most use this methods :

<br>

### Step 1 — Generating an Origin CA TLS Certificate

<br>

To generate a certificate with Origin CA, navigate to the Crypto section of your Cloudflare dashboard. From there, click on the Create Certificate button in the Origin Certificates section

<br>

We’ll use the `/etc/ssl/certs` directory on the server to hold the origin certificate. The `/etc/ssl/private` directory will hold the private key file. Both folders already exist on the server.

<br>

First, copy the contents of the Origin Certificate displayed in the dialog box in your browser.

Then, on your server, open `/etc/ssl/certs/cert.pem` for editing:

```sh
sudo nano /etc/ssl/certs/cert.pem
```

Paste the certificate contents into the file. Then save and exit the editor.

Then return to your browser and copy the contents of the Private key. Open the file /etc/ssl/private/key.pem for editing:

```sh
sudo nano /etc/ssl/private/key.pem
```

Paste the key into the file, save the file, and exit the editor.
<br>

### Step 2 — Installing the Origin CA certificate in Nginx

<br>

open the Nginx configuration file for your domain:

```
sudo nano /etc/nginx/sites-available/api.exiloom.com
```

We’ll modify the Nginx configuration file to do the following:

<br>

Listen on port 80 and redirect all requests to use https.
Listen on port 443 and use the origin certificate and private key that you added in the previous section.
Modify the file so it looks like the following:

<br>

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name api.exiloom.com;
    return 302 https://$server_name$request_uri;
}

server {

    # SSL configuration

    listen 443 ssl http2;
    listen [::]:443 ssl http2;


    ssl_certificate         /etc/ssl/certs/cert.pem;
    ssl_certificate_key     /etc/ssl/private/key.pem;

    server_name api.exiloom.com www.api.exiloom.com;

    root /var/www/exiloom/public;
    index index.php index.html index.htm index.nginx-debian.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Save the file and exit the editor.

Next, test to make sure that there are no syntax errors in any of your Nginx configuration files:

```
sudo nginx -t
```

If no problems were found, restart Nginx to enable your changes:

```
sudo systemctl restart nginx
```

<br>

Now go to the Cloudflare dashboard’s Crypto section and change SSL mode to Full. This informs Cloudflare to always encrypt the connection between Cloudflare and your origin Nginx server.

<br>

## How to fix Error: laravel.log could not be opened?

<br>

Never set a directory to 777. you should change directory ownership. so set your current user that you are logged in with as owner and the webserver user (www-data, apache, ...) as the group. You can try this:

```
sudo chown -R $USER:www-data storage
```

then to set directory permission try this:

```
chmod -R 775 storage
```

you permission error most be fixed .

<br>

## Use phpmyadmin

if you want yo access phpmyadmin web page you most open this url in your browser

```
http://SERVER_IP:${PMA_PORT}
```

you can see `PMA_PORT` from `.env.example` file .

### how can proxy phpmyadmin on webaddress ?

if you want access to phpmyadmin webpage from url you most be create `phpmyadmin` file in `/etc/nginx/sites-avalible/` whit this command :

```
nano /etc/nginx/sites-available/phpmyadmin
```

and paste this cone in editor :

```nginx

server {
    listen 80;
    listen [::]:80;
    server_name pma.exiloom.com;
    return 302 https://$server_name$request_uri;
}

server {

    # SSL configuration

    listen 443 ssl http2;
    listen [::]:443 ssl http2;


    ssl_certificate         /etc/ssl/certs/cert.pem;
    ssl_certificate_key     /etc/ssl/private/key.pem;

    server_name pma.exiloom.com www.pma.exiloom.com;


    location / {
        proxy_set_header   X-Forwarded-For $remote_addr;
        proxy_set_header   Host $http_host;
        proxy_pass         "http://SERVER_IP:8080";
    }


```

after that save and exit editor and toy most be enabling this setting on nginx with this command :

```
sudo ln -s /etc/nginx/sites-available/phpmyadmin /etc/nginx/sites-enabled/
```

after that restart nginx server

```
systemctl restart nginx
```

## License

All right reserved by Exiloom Exchange [Official Website](https://Exiloom.com).
