FROM rabbitmq:3-management

ARG UID

RUN apt-get update \
    && apt-get install -y vim

RUN usermod -u ${UID} www-data

EXPOSE 9000