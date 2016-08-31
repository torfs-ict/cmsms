#!/usr/bin/env bash

# Make sure the `add-apt-repository` command is available
sudo apt-get install -y software-properties-common

# Add Ansible Repository & Install Ansible
sudo add-apt-repository -y ppa:ansible/ansible
sudo apt-get update
sudo apt-get install -y ansible

# Setup Ansible for Local Use and Run
sudo cp /vagrant/ansible/inventories/dev /etc/ansible/hosts -f
sudo chmod 666 /etc/ansible/hosts
mkdir /home/vagrant/.ssh 2> /dev/null
cat /vagrant/ansible/files/authorized_keys >> /home/vagrant/.ssh/authorized_keys
sudo ansible-playbook /vagrant/ansible/playbook.yml -e hostname=cmsms --connection=local