#!/bin/bash

GREP=$(grep "$1" /usr/lib/cgi-bin/pbid)
token=($GREP)

echo ${token[$2]}

