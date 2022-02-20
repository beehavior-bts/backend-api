# backend-api

This service provide backend Rest API, webhook listener and SQL database

## Introduction

## Quickstart

### Requirements 

- Linux host (virtualized or bare metal)
- 4GB Memory
- Git
- Docker Latest Version
- Docker Compose Latest Version

> Attention ! 4GB of memory is only for building dockerfile
> You can run the entire program with only 1.5GB of memory

#### Install Requirements Debian

```sh
$ sudo apt remove docker docker-engine docker.io containerd runc
$ sudo apt update ca-certificates curl gnupg lsb-release
$ curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
$ echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
$ sudo apt update
$ sudo apt install docker-ce docker-ce-cli containerd.io docker-compose git
```

#### Install Requirements Ubuntu

```sh
$ sudo apt remove docker docker-engine docker.io containerd runc
$ sudo apt update ca-certificates curl gnupg lsb-release
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
$ echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
$ sudo apt update
$ sudo apt-get install docker-ce docker-ce-cli containerd.io docker-compose git
```

#### Install Requirements Fedora

```sh
$ sudo dnf remove docker docker-client docker-client-latest docker-common docker-latest docker-latest-logrotate docker-logrotate docker-selinux docker-engine-selinux docker-engine
$ sudo dnf -y install dnf-plugins-core
$ sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo
$ sudo dnf install docker-ce docker-ce-cli containerd.io
```

### Building && Launching

```sh
$ git clone git@github.com:beehavior-bts/backend-api.git
$ cd backend-api
$ sudo docker-compose up -d
```