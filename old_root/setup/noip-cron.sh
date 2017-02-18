#!/bin/bash
IP=$(curl --silent http://ipinfo.io/ip)
USERNAME="username@host.com"
PASSWORD="password"
HOSTNAME="hostname.ddns.net"
HEADER=$(echo "${USERNAME}:${PASSWORD}" | openssl base64 -e)

curl --silent --header "Authorization: Basic ${HEADER}" "https://dynupdate.no-ip.com/nic/update?hostname=${HOSTNAME}&myip=${IP}"
