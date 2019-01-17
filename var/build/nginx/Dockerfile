FROM debian:jessie

RUN apt-get update && apt-get install --no-install-recommends -y locales \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*
RUN locale-gen en_US.UTF-8
ENV LANG       en_US.UTF-8
ENV LANGUAGE   en_US:en
ENV LC_ALL     en_US.UTF-8
ENV MODULESDIR /root/nginx-modules
ENV NGINX_VERSION 1.13.0

RUN apt-get update &&  apt-get -y install nginx wget build-essential zlib1g-dev libpcre3 libpcre3-dev libbz2-dev libssl-dev tar unzip --no-install-recommends && rm -r /var/lib/apt/lists/*

RUN cd /root && wget http://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz && tar xf nginx-${NGINX_VERSION}.tar.gz && rm -f nginx-${NGINX_VERSION}.tar.gz

RUN mkdir -p /var/log/nginx/ && mkdir -p /etc/nginx/ && mkdir -p /usr/share/ && mkdir -p /var/lock/

RUN cd /root/nginx-${NGINX_VERSION} && ./configure --prefix=/usr/share/nginx --sbin-path=/usr/sbin/nginx --conf-path=/etc/nginx/nginx.conf --error-log-path=/var/log/nginx/error.log --http-log-path=/var/log/nginx/access.log --pid-path=/var/run/nginx.pid --lock-path=/var/lock/nginx.lock --with-http_v2_module --with-http_ssl_module  --user=www-data --group=www-data --with-ipv6 --with-http_stub_status_module --with-http_gzip_static_module --with-http_addition_module --with-http_realip_module --without-mail_pop3_module --without-mail_imap_module  --without-mail_smtp_module

RUN cd /root/nginx-${NGINX_VERSION} && make && make install && make clean
RUN rm -R /root/*

CMD ["nginx", "-g", "daemon off;"]

HEALTHCHECK --interval=5m --timeout=3s CMD curl -f http://localhost/ || exit 1

# Add required nginx config
ADD nginx.conf /etc/nginx/nginx.conf
ADD symfony.conf /etc/nginx/sites-enabled

WORKDIR /var/www/symfony
