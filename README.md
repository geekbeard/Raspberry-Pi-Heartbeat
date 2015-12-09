Raspberry-Pi-Heartbeat
======================

##Status webpage for Raspberry Pi##

![Raspberry Pi Heartbeat](http://i.imgur.com/nEqeHF6.png "Latest version screenshot:")

###Prerequisites###
You will have to check a few things for this page to show up correctly. You need to have:

* lsb-release (for non Raspbian distributions) - so the automated installer script can find your distribution name.
* Webserver (apache \ lighttpd \ ..) with PHP


#####Optional (./install.sh will install them for you):
* Sqlite for PHP `$ sudo apt-get install php5-sqlite`
* PHP Safe Mode = off
* vstat installed `$ sudo apt-get install vnstat`

First 3 are more or less general and common. VNSTAT is additional app which collects traffic information. You might fiddle around with it's settings after the installation if you want. Main requirement is that it does give an output with this command `$ vnstat --dumpdb`.

###Contents of the package & Installation###

As for now: 8-9 files

* config.php - Where you can configure all the settings (folder path, verbosity and future options)
* index.php - Home page which shows the graphs and info.
* style.css - Styles for the fashion!
* rpiTemp.db - SQLite db file. With some test data.
* measuretemp.php - php script for cron job.
* cleanDB.php - script to clean the DB from sampling data and forbid future accidental deletions using a lock file.
* db.lck - lock file used by cleanDB.php to flag the clean operation as done and forbid future database cleanups. If you do want to reset the database, then delete db.lck first.
* funs.php - extras for Ajax and live measurements

**To set it up:**

1. Download a zip from [this page] (http://geekbeard.github.io/Raspberry-Pi-Heartbeat/) and unzip to a desired web folder (i.e. /var/www/rpih/)
2. Open config.php and update `$config["root_dir"]` the with directory you put it in. For example, if you put it into /var/www/rpih/: `$config["root_dir"]="/var/www/rpih/"`
3. Check and install if needed the prerequisites (see above)  OR 
4. Run `$ sudo ./install.sh` - this will try to install all missing prerequisites, initialize the database with the first temperature sample and update the crontab with the periodical temperature sampling. Default is a sample every 5 minutes. This can later be changed in `$ sudo crontab -e`

You can also manually add the cron job to your system/download the
packages/clean the database. `$ sudo crontab -e` and add a line at the end of the file: 
`*/5 * * * * php /path/measuretemp.php` - /path/ is the place where you've
unziped the package! This will run measuretemp.php every 5 minutes. If you want
it to happen less often, change 5 to a higher number of minutes (*/10 for every
10 minutes, */35 for every 35 minutes and so on).

The database can be cleaned using cleanDB.php, which will also produce a lock
file to avoid future  accidental deletions. Delete the lock file (`db.lck`) first if you
really want to clean the database.

**You are done!**

You've unzipped the package, created a scheduled task for Raspbian to run the php script every N minutes and you have cleaned the database from test data and the script created a fail safe flag (file: `db.lck`) which will prevent anyone from cleaning db again!

Access the webpage with the web browser and start collecting statistics!:)

And of course come back for updated versions!

###Authors and Contributors###
First version by: @beard

###Support or Contact###
If you have any questions \ suggestions \ feedbacks: geekbearddev@gmail.com - we hope you send us a note!:)

Please feel free to fork and improve!
