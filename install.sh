#!/bin/bash

#Install dependencies in Ubuntu if required
function install_ubuntu() {
	#TODO
	0
}

#Install dependencies in Arch Linux if required
install_arch() {
	echo "Installing dependencies for Arch Linux..."
	pacman -Syy
	pacman -S vnstat lsb-release php-sqlite

}



DISTRO=$(lsb_release -sd)
echo $DISTRO

#TODO ADD OTHER DISTROS
if [ "$DISTRO"=="Arch Linux" ];
then
	install_arch
elif [ "$DISTRO"=="Ubuntu" ];
then
	install_ubuntu
else
	echo "Your distribuition $DISTRO is not supported by this installer (yet)."
	exit 1
fi



