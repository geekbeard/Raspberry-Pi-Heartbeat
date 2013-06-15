#!/bin/bash

#Try to install lsb_release to get distro information
#TODO add support for rasbian uname?
#y: raspbian doesnt have lsb_release and can't be installed - any other way to get the right distro?
DISTRO=$(lsb_release -sd)

echo distro:$DISTRO

PWD=$(pwd)
#echo pwd:$PWD

#Add measure temp to crontab
function update_crontab() {
	sampling_interval="5"
	tmp_cronf="tmp_cronf"
	crontab -l > $tmp_cronf
	echo "*/$sampling_interval * * * * php $PWD/measuretemp.php" >> $tmp_cronf
	crontab $tmp_cronf
	echo "Crontab updated: Sampling temperature every $sampling_interval minutes." 
}

#Install dependencies in Ubuntu if required
function install_raspbian() {
	#TODO
	0
}

#Install dependencies in Arch Linux if required
install_arch() {
	packages=" vnstat lsb-release php-sqlite"
	echo "Installing dependencies ($packages) for Arch Linux..."
	pacman -Syy
	pacman -S $packages	
}




#TODO ADD OTHER DISTROS
if [ "$DISTRO"=="Arch Linux" ];
then
	install_arch
	update_crontab
	php cleanDB.php
elif [ "$DISTRO"=="Raspbian" ];
then
	install_ubuntu
	update_crontab
	php cleanDB.php
else
	echo "Your distribuition '$DISTRO' is not supported by this installer (yet)."
	exit 1
fi



