# Rebost - gestió de magatzem d'imatges de vagrant

## 1. Introducció

## 2. Arquitectura
```
/var/www/html/              ← DocumentRoot d'Apache 
    rebost/web/             ← Aplicació accessible des de web http://localhost/rebost/web/index.php
        index.php           Pàgina inicial amb 3 pestanyes (login, pujada d'imatges i llistat)
        login.php
        logout.php
        pujar.php
        descarregar.php
        includes/config.php ← Define de paths cap a carpetes de magatzematge, usuaris i missatges d'error

/var/www/dades/             ← FORA del DocumentRoot: no accessible por URL
    config/usuaris.txt      Llistat d'usuaris(només lectura)
    content/
        temp/               Carpeta on es guardaran les pujades temporals, fins tenir totes les parts.
        images/             Carpetes per a emmagatzemar les imatges.
```

Una de les condicions era que el fitxer d'usuari no estigui accessible desde fora.
Vaig trobar la opció de gestionar-ho fora de l'Apache, i que sigui accessible des del php.


## 3. Instal·lació

```bash

# 1) Des del servidor on fem l'instal·lació, fem un git clone del repositori

cd /var/www/html/
git clone https://github.com/mlustrino/rebost.git

sudo mv /var/www/html/rebost/rebost/web/dades /var/www/
sudo mkdir -p /var/www/dades/content/ /var/www/dades/content/images /var/www/dades/content/temp

sudo chown $USER:www-data /var/www/dades /var/www/dades/config /var/www/dades/config/usuaris.txt
sudo chmod 750  /var/www/dades
sudo chmod 2750 /var/www/dades/config
sudo chmod 640  /var/www/dades/config/usuaris.txt
sudo chmod -R 750 /var/www/dades/content

# 2) Moure fitxers

sudo mv /var/www/html/rebost/crear_hash.php /var/www/
sudo chown www-data:www-data /var/www/crear_hash.php


# 3) Crear usuaris
php crear_hash.php >> /var/www/dades/config/usuaris.txt

```
