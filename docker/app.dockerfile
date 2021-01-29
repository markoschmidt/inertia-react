FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libmagickwand-dev \
  locales \
  python2.7 \
  python-pip \
  supervisor \
  unzip \
  zlib1g-dev \
  libzip-dev

RUN pecl install imagick

RUN docker-php-ext-install exif && \
  docker-php-ext-enable exif && \
  docker-php-ext-enable imagick && \
  docker-php-ext-configure gd --with-freetype-dir=/usr/include/ \
  --with-jpeg-dir=/usr/include/ && \
  docker-php-ext-install bcmath gd pdo_mysql zip

RUN sed -i -e 's/# fi_FI.UTF-8 UTF-8/fi_FI.UTF-8 UTF-8/' /etc/locale.gen && \
  dpkg-reconfigure --frontend=noninteractive locales && \
  update-locale LANG=fi_FI.UTF-8

RUN locale-gen fi_FI.UTF-8

ENV LANG fi_FI.UTF-8
ENV LANGUAGE fi_FI:fi
ENV LC_ALL fi_FI.UTF-8

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN sed -i -e 's/post_max_size = [0-9]*M/post_max_size = 24M/' /usr/local/etc/php/php.ini
RUN sed -i -e 's/upload_max_filesize = [0-9]M/upload_max_filesize = 24M/' /usr/local/etc/php/php.ini

ADD docker/app/supervisor.conf /etc/supervisor/conf.d/laravel-worker.conf

CMD supervisord -c /etc/supervisor/supervisord.conf && php-fpm
