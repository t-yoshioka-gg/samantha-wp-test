FROM wordpress

ARG WORDPRESS_DB_HOST
ARG WORDPRESS_DB_USER
ARG WORDPRESS_DB_PASSWORD
ARG WORDPRESS_DB_NAME

# 必要なソフトウェア郡のインストール
RUN apt-get update -y && apt-get install -y p7zip \
    p7zip-full \
    git \
    nodejs \
    npm \
    zip \
    unzip \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# userの設定
RUN useradd -ms /bin/bash vscode \
	&& usermod -aG www-data vscode

RUN curl --location --output /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x /usr/local/bin/wp && \
    /usr/local/bin/wp --info

RUN wp core download --locale=ja --path=/var/www/html/ --allow-root

 RUN chmod 777 -R /var/www/html/
