Raspberry-Pi-Heartbeat
======================

#Status webpage for Raspberry Pi#

Prerequisites
You will have to check a few things for this page to show up correctly. You need to have:

Webserver (apache \ lighttpd \ ..) with PHP
Sqlite for PHP $ sudo apt-get install php5-sqlite
PHP Safe Mode = off
vnstat installed $ sudo apt-get install vnstat
First 3 are more or less general and common. VNSTAT is additional app which collects traffic information. You might fiddle around with it's settings after the installation if you want. Main requirement is that it does give an output with this command vnstat --dumpdb.

Contents of the package & Installation
As for now: 5-6 files

index.php - Home page which shows the graphs and info.
style.css - Styles for the fashion!
rpiTemp.db - SQLite db file. With some test data.
measuretemp.php - php script for cron job.
cleanDB.php - script to clean the DB from test data!
*cl.php - this file will be created by cleanDB.php as a flag to mark the operation as done.
To set it up:

Check and install if needed the prerequisites (see above)
Download a zip from this page.
Put it into a web server folder on your RPi (i.e. /var/www/ )
Unzip.
Setup a cron job as root $ sudo crontab -e and add a line at the end of the file:
*/5 * * * * php /var/www/measuretemp.php - /var/www/ is the place where you've unziped the package!

This will run measuretemp.php every 5 minutes. If you want it to happen less often, change 5 to a higher number of minutes (*/10 for every 10 minutes, */35 for every 35 minutes and so on).

CLEAN DB: in your browser open http://rpi.IP/your_path/cleanDB.php
You are done!

You've unzipped the package, created a scheduled task for Raspbian to run the php script every N minutes and you have cleaned the database from test data and the script created a fail safe flag (file: cl.php) which will prevent anyone from cleaning db again!

Access the webpage with the web browser and start collecting statistics!:)

And of course come back for updated versions!

Authors and Contributors
First version by: @yuraa

Support or Contact
If you have any questions \ suggestions \ feedbacks: rpih@yuraa.com - we hope you send us a note!:)

Also in case you want to join as a contributor - send a message to us. (project will go public once it has a clean code and comments for others to be able to contribute easily).
