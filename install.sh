#!/bin/bash

#Try to install lsb_release to get distro information
#TODO add support for rasbian uname? 
#y: raspbian doesnt have lsb_release and can't be installed - any other way to get the right distro?
#DISTRO=$(lsb_release -sd)
DISTRO=$(uname -n)

echo distro:$DISTRO

PWD=$(pwd)
echo pwd:$PWD

#Add measure temp to crontab
update_crontab() {
	sampling_interval="5"
	tmp_cronf="tmp_cronf"
	crontab -l > $tmp_cronf
	echo "*/$sampling_interval * * * * php $PWD/measuretemp.php" >> $tmp_cronf
	crontab $tmp_cronf
	echo "Crontab updated: Sampling temperature every $sampling_interval minutes." 
}

#Install dependencies in Ubuntu if required
install_ubuntu() {
	#TODO
	sqliteinst=$(dpkg -l | grep php5-sqlite)
	vnstatinst=$(dpkg -l | grep vnstat)
	vnstatshort=${vnstatinst:0:2}
	sqlshort=${sqliteinst:0:2}
	if [ "$sqlshort" = "ii" ]; then
		echo "Sqlite is installed."
		
	else
		echo "Need to install Sqlite."
		echo "Installing Sqlite for PHP"
		apt-get update
		apt-get -y install php5-sqlite
		exit 1
	fi
	if [ "$vnstatshort" = "ii" ]; then
		echo "vnstat is installed."
		
	else
		echo "Need to install vnstat."
		echo "Installing vnstat for PHP"
		apt-get update
		apt-get -y install vnstat
		exit 1
	fi
}

#Install dependencies in Arch Linux if required
install_arch() {
	packages=" vnstat lsb-release php-sqlite"
	echo "Installing dependencies ($packages) for Arch Linux..."
	pacman -Syy
	pacman -S $packages	
}

#TODO ADD OTHER DISTROS
if [ "$DISTRO" = "Arch Linux" ]; then
	install_arch
	update_crontab
	php cleanDB.php
	exit 1
elif [ "$DISTRO" = "raspberrypi" ]; then
	install_ubuntu
	update_crontab
	php cleanDB.php
	exit 1
else
	echo "Your distribuition '$DISTRO' is not supported by this installer (yet)."
	exit 1
fi



