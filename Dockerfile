FROM php:7.4-apache

# Install and enable MySQLi extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable mod_rewrite and mod_headers
RUN a2enmod rewrite headers

# Update the Apache configuration to allow access to the document root and set CORS headers
RUN echo "<Directory /var/www/html>\n\tAllowOverride All\n\tRequire all granted\n\tHeader add Access-Control-Allow-Origin \"*\"\n</Directory>" > /etc/apache2/conf-available/custom.conf && a2enconf custom

# Copy your source files to the container
COPY . /var/www/html/

# Set proper file permissions
RUN chown -R www-data:www-data /var/www/html/
