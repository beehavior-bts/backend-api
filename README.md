# backend-api

This service provide backend Rest API, webhook listener and SQL database

## Table of contents

- [Introduction](#introduction)
- [Architecture](#architecture)
- [Quickstart](#quickstart)
  - [System Requirements](#requirements)
    - [Install Requirements Debian](#install-requirements-debian)
    - [Install Requirements Ubuntu](#install-requirements-ubuntu)
    - [Install Requirements Fedora](#install-requirements-fedora)
    - [Install Requirements Arch Linux](#install-requirements-arch-linux)
  - [Building and Launching](#building--launching)
- [API Documentation](#api-documentation)
- [License](#license)

## Introduction

## Architecture

![Architecture Of BeeHavior Backend](https://user-images.githubusercontent.com/64791937/154861399-3d7c7cbb-50aa-4273-af98-e3afac526ffa.png)

## Quickstart

### Requirements 

- Linux host (virtualized or bare metal)
- Memory :
  - 3GB Memory for docker build
  - 1.5GB Memory for docker run
- Git
- Docker Latest Version
- Docker Compose Latest Version

#### Install Requirements Debian

```sh
$ sudo apt remove docker docker-engine docker.io containerd runc
$ sudo apt install -y ca-certificates curl gnupg lsb-release curl
$ curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
$ echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
$ sudo apt update
$ sudo apt install docker-ce docker-ce-cli containerd.io git
$ sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
```

#### Install Requirements Ubuntu

```sh
$ sudo apt remove docker docker-engine docker.io containerd runc
$ sudo apt install -y ca-certificates curl gnupg lsb-release curl
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
$ echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
$ sudo apt update
$ sudo apt-get install docker-ce docker-ce-cli containerd.io git
$ sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
```

#### Install Requirements Fedora

```sh
$ sudo dnf remove docker docker-client docker-client-latest docker-common docker-latest docker-latest-logrotate docker-logrotate docker-selinux docker-engine-selinux docker-engine
$ sudo dnf -y install dnf-plugins-core curl
$ sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo
$ sudo dnf install docker-ce docker-ce-cli containerd.io git docker-compose
```

#### Install Requirements Arch Linux

```sh
$ sudo pacman -S docker git docker-compose curl
```

### Building && Launching

```sh
$ git clone https://github.com/beehavior-bts/backend-api.git
$ cd backend-api
$ sudo systemctl start docker
$ sudo docker-compose up -d --build
```

# API Documentation

## Login to an account

### Request

`POST /api/auth/login`

    curl -X POST -i -H 'Content-Type: application/json' http://beehavior.com/api/auth/login -d '{"email": "admin@beehavior.com", "password": "Patate#12345"}'

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.21.4
    Date: Mon, 21 Feb 2022 14:05:32 GMT
    Content-Type: application/json
    Content-Length: 959
    Connection: keep-alive
    access-control-allow-credentials: true
    set-cookie: Token-Account=<YOUR_TOKEN>; Domain=.beehavior.com; HttpOnly; Max-Age=10800; Path=/; SameSite=Strict

    {"title": "OK", "description": "Success to login", "content": {"id": "513cbde1c5f64315b88e75fa6cd71dc7", "username": "Admin", "token": "<YOUR_TOKEN>"}}

## Get account informations

### Request

`GET /api/account/info`

    curl -X GET -i -H 'Authorization: <YOUR_TOKEN>' http://beehavior.com/api/account/info

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.21.4
    Date: Mon, 21 Feb 2022 19:52:31 GMT
    Content-Type: application/json
    Content-Length: 224
    Connection: keep-alive

    {"title": "OK", "description": "Sucess to get account and hives info", "content": {"id": "513cbde1c5f64315b88e75fa6cd71dc7", "username": "Admin", "email": "admin@beehavior.com", "phone": null, "is_admin": true, "hives": []}}

# License

MIT License

Copyright (c) 2022 beehavior-bts

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.