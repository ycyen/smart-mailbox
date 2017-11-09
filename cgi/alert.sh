#!/bin/bash

TIME=$(date --date=@$1 +%Y/%m/%d\ %H:%M:%S)

echo "{\"type\": \"link\", \"title\": \"You've Got A Mail\", \"body\": \"at $TIME\", \"url\": \"http://youvegotamail.csie.org/\"}" | curl -s -u $2: -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary @-

