#!/bin/bash
yum -y install perl

read -p "Enter your hostname : " host
echo "hostname $host"
cd /home
curl -o latest -L https://securedownloads.cpanel.net/latest
//sh latest
