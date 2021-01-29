FROM nginx:1.15

RUN apt-get update && apt-get install -y locales

RUN sed -i -e 's/# fi_FI.UTF-8 UTF-8/fi_FI.UTF-8 UTF-8/' /etc/locale.gen && \
  dpkg-reconfigure --frontend=noninteractive locales && \
  update-locale LANG=fi_FI.UTF-8

RUN locale-gen fi_FI.UTF-8

ENV LANG fi_FI.UTF-8
ENV LANGUAGE fi_FI:fi
ENV LC_ALL fi_FI.UTF-8

RUN sed -i -e 's/user  nginx;/user  www-data;/' /etc/nginx/nginx.conf;

ADD docker/web/vhost.conf /etc/nginx/conf.d/default.conf
