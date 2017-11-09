#!/bin/bash
curtime=$(date +%s)
PBID="/usr/lib/cgi-bin/pbid"

IDEN=$(curl -s --header "Authorization: Bearer $2" https://api.pushbullet.com/v2/users/me |perl -ne 'print "$1\n" if /"iden":"([^"]+)"/';)
GREP=$(grep " $IDEN" "$PBID")

if [ "$1" = "1" ]
then
	echo $IDEN
	exit
fi

if [ -z "$GREP" ]
then
	echo -e "First time login. Please use this id to log in your Raspberry Pi: $IDEN"
	echo "$2 $IDEN" >> "$PBID"
	echo "$curtime" > "/var/www/time/$IDEN"
	echo {\"type\": \"note\", \"title\": \"Welcome\", \"body\": \"Smart MailBox\"} | curl -s -u $2: -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary @- $> /dev/null 
else
	echo "Welcome back."
	#echo {\"type\": \"note\", \"title\": \"已有訂閱記錄\", \"body\": \"您好，您先前已經有訂閱了喔，謝謝。\"} | curl -s -u $2: -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary @-
fi
