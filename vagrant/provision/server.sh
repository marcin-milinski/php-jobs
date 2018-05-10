#!/bin/bash

echo "Configuring Nginx and Apache for Job.pl ..."
# we use "include..." to link back to the server conf file stored within this project repo, so no extra folder sync is needed
# Nginx
echo "include /var/www/job.pl/server/nginx.conf;" | sudo tee /etc/nginx/sites-available/job.pl > /dev/null
sudo ln -sf /etc/nginx/sites-available/job.pl /etc/nginx/sites-enabled/job.pl
# Apache
echo "Include /var/www/job.pl/server/apache.conf" | sudo tee /etc/apache2/sites-available/job.pl.conf > /dev/null
sudo a2ensite job.pl
echo "Configuration finished"