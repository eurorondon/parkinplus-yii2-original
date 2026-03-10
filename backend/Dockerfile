FROM yiisoftware/yii2-php:7.4-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf

# Increase PHP memory limit
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini