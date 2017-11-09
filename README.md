# smart-mailbox
DSL final project - auto notification of new mail on mailbox.


## Please follow these steps to setup your Smart MailBox

### Server Side
1. install apache and php
2. put files in 'web' into /var/www (your web root)
3. folder 'log', 'time' need to be writable and '*.php' need to be readable by user 'www-data'
4. put files in 'cgi' into /usr/lib/cgi-bin
5. file 'pbid' need to be writable and '*.sh' need to be readable by user 'www-data'
6. service apache2 start


### Pushbullet Service Registration
1. goto https://www.pushbullet.com/create-client
2. login to your Pushbullet account
3. set the url of your web server (website: index.php, image: faviconsq.gif, redirect: index.php)
4. you'll get client_id and client_secret, replace the ones in index.php with what you got


### Raspberry Side

腳位:
將紅外線發射/接收電路的Vdd接至pi的四號腳位(5V)
Ground接於六號腳位
Signal接於七號腳位
利用python的函式庫RPi.GPIO來讀七號腳位的訊號
高電位 = 1, 低電位 = 0
那麼我們就可以知道有物體(信)通過紅外發射/接收的路徑(信箱的開口)時
必會產生由低電位變至高電位的變化
因此我們就可以用一個迴圈來判定電位是否發生變化
並在變化時利用urllib函式庫 以http的get方法將事件發生的時間送至伺服器
然後將伺服器回傳的內容做為除錯訊息印出

0. copy the file IR.py in folder rpi to your Raspberry pi
1. set user in IR.py as the user id you got when the first time you login to the web page using Pushbullet
2. set url in IR.py to your server, the parameters after index.php should be kept
3. make sure you have sensor and internet connected to Raspberry pi
4. run IR.py
