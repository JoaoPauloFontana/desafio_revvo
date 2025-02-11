FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite \
    && apt-get clean

COPY . /var/www/html
WORKDIR /var/www/html/public

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN echo "<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom \
    && service apache2 restart

RUN echo "display_errors=On" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php.ini

EXPOSE 80

CMD ["apache2-foreground"]
