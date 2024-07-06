# Utiliser l'image officielle PHP
FROM php:7.4-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier les fichiers de l'application dans le répertoire de l'image Docker
COPY . /var/www/html/

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]