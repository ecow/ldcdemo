# -*- mode: ruby -*-
# vi: set ft=ruby :

# elinux/v01642 is 
VBOX         = "ubuntu/trusty32" #just a plain ubuntu 14.04 server, fill free to change with ypur preferred distribution.
VBOX_MEMORY  = 640
LOCALPORT	 = 8080			   # change this if already in use


$provisionScript = <<SCRIPT
echo 'I am provisioning lcdemo...'
apt-get update
apt-get -y install apache2 libapache2-mod-php5 php5-curl 
# link pub to document root
ln -s /vagrant/pub/ /var/www/html/demo

## Uncomment following lines if you want to develop library code: 
## install composer and enable display_errors
sed -i 's/^display_errors = Off/display_errors = On/g' /etc/php5/apache2/php.ini
apt-get -y install git subversion
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
cd /vagrant; composer install

echo '--- Your code is in /vagrant directory'
echo '--- Use "vagrant ssh" to login to virtual host'
echo '--- Use "vagrant destroy" to stop virtual host and release resources' 
echo '--- Point your browser to "http://localhost:#{LOCALPORT}/demo"'


SCRIPT

VAGRANTFILE_API_VERSION = '2'
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    config.vm.provider :virtualbox do |vb|
        config.vm.box = VBOX
        vb.memory = VBOX_MEMORY
        ## Try uncomment next line if you experience problems booting virtual box
		#vb.gui = true
        config.vm.network "forwarded_port", guest: 80, host: LOCALPORT
    end

	config.vm.provision "shell", inline: $provisionScript
end
