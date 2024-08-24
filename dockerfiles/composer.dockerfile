FROM composer:2

# disable because running isolated module, make it dynamic get from current system UID GID
# ARG UID
# ARG GID

ENV UID=1000
ENV GID=1000

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

WORKDIR /var/www/html
