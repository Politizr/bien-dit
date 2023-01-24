BIEN DIT
=====================

# Description

Bien Dit est un service de consultations en ligne pour les Collectivités et les Associations.

![bd-desk-consult-home2](https://user-images.githubusercontent.com/233263/214281784-1c86999b-5b15-4f9f-9f24-52bece146d00.jpg)
![bd-desk-consult-home3](https://user-images.githubusercontent.com/233263/214281789-fbf3e966-edb6-44de-9822-47e2b40ee25a.jpg)
![bd-desk-consult-list](https://user-images.githubusercontent.com/233263/214281792-9fa090c3-5a49-4f9c-a5a5-04e2116f1619.jpg)
![bd-desk-consult-notif](https://user-images.githubusercontent.com/233263/214281804-5037567a-861c-46f6-93b1-7ee4f4efbe6d.jpg)
![bd-desk-consult-sujet](https://user-images.githubusercontent.com/233263/214281806-92fd9e3b-ba43-4c55-8f1e-55a68b80496c.png)

Ce service est destiné aux collectivités désireuses d’ouvrir un canal de discussion adapté, protégé et indépendant avec leurs concitoyen.ne.s. BIEN DIT est tout à la fois simple, constructif et paramétrable! 

# Compatibilité requise

PHP >=7.0 & <= 7.1

# Installation

## Clonage des répos Github

```
git clone git@github.com:Lionel09/bien-dit.git my-project
git clone git@github.com:Lionel09/StudioEchoBundles.git my-project/src/StudioEchoBundles
```

## Création des répertoires & MAJ des droits

```
mkdir app/cache app/cache/htmlpurifier app/logs app/sessions web/uploads
```

Décompression & copie des assets par défaut dans web/uploads

[biendit-assets.zip](https://github.com/lionelbzv/bien-dit/files/10489166/biendit-assets.zip)

```
sudo setfacl -R -m u:www-data:rwx -m u:'adminwww':rwx app/cache app/logs app/sessions web/uploads
sudo setfacl -dR -m u:www-data:rwx -m u:'adminwww':rwx app/cache app/logs app/sessions web/uploads
```

## MAJ du parameters.yml

Fichier à MAJ suivant votre config

```
app/config/parameters.yml
```

## Installation des dépendances

```
composer install
app/console propel:model:build
app/console assetic:dump --no-debug
cd src/Politizr/FrontBundle/Resources/public/css
sass --update styleUser.scss:styleUser.css
```

## Plus de détails

Coincé dans l'installation ? Contacter @lionelbzv directement !


# LICENCE

MIT License

Copyright (c) [2014-2023] [Lionel Bouzonville]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
