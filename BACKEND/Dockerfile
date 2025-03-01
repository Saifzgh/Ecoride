FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Activer les modules Apache
RUN a2enmod rewrite

# Copier les fichiers du backend
COPY . /var/www/html

# Définir le dossier public comme root
WORKDIR /var/www/html/public
