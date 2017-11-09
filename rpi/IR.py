import RPi.GPIO as GPIO
import time
import urllib

# Use physical pin numbers
GPIO.setmode(GPIO.BOARD)
sensorPin = 7
GPIO.setup(sensorPin, GPIO.IN)
prev_input = 0
i = 1
user = "ujDi2SOYzcW"

while True:
        input = GPIO.input(sensorPin)
        #if the last reading was low and this one high, print
        if ((not prev_input) and input):
                int_time = int(time.time())

                debug = "%d mail got!" % (i)
                url = "http://54.169.66.216/log.php?time=%d&user=%s" % (int_time,user)
                response = urllib.urlopen(url).read()
                print debug
                print response

                i = i+1
                time.sleep(0.3)
        prev_input = input