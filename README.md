#FBDEV - Facebook APP ESGI | BERSECK

# Configure your environment for DEV

## On Mac & Linux

* Download and use MAMP or WAMP or directly Apache 
* Set Apache MAMP to port **80**
* Add a vhosts which will point to the folder **htdoc/fbdev/** with the hostname **berseck.fbdev.fr**
* Clone this repo
* Do the command `composer.phar install`
* Edit the **hosts files** using the following command `sudo nano /etc/hosts`
* Add the following line `127.0.0.1  berseck.fbdev.fr`
* Download the dump of the database on the drive and import it in yout local database
* Update the **config.inc.php** with your own configuration
* Start the project by starting the MAMP 

## On windows

Same process as Mac. Note that the hosts is in **System32/driver/etc/hosts**

# Coding style

## JavaScript

TBD

## PHP 

TBD
