# BAPP - Die Buchungs-App

## Introduction

## Installation
```bash
apt-get install language-pack-de
```

## Docker
```
FROM php:7.4-fpm
# FROM php:8.0-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt update \
 && apt install -y libxrender1 libfontconfig libxext6
 && apt clean
```
