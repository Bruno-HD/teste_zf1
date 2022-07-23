FROM php:7.4.6-apache
WORKDIR /var/www

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


# Pacotes necessários para esta aplicação
RUN apt-get update && \
    apt -y install \   
            libssl-dev \
            libzip-dev \        
            libsqlite3-dev \
            libldb-dev \
            libldap2-dev \
            unzip \
            wget \
            g++ \
            git \
            libpq-dev \
            build-essential && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#2. Ativando .htacess e redirecionamento 
RUN a2enmod rewrite

#3. Instalando extensões necessárias para rodar este projeto
RUN docker-php-ext-install calendar 
RUN docker-php-ext-install ctype 
RUN docker-php-ext-install zip 
RUN docker-php-ext-install session 
RUN docker-php-ext-install json 
RUN docker-php-ext-install sockets 
RUN docker-php-ext-install pdo 
RUN docker-php-ext-install pdo_pgsql 
RUN docker-php-ext-install gettext 
RUN docker-php-ext-install tokenizer 
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install ldap
