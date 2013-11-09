cd $(dirname $0)
clear
echo 'Enter your password:'
sudo rm -f eventkit.zip
sudo rm -rf eventkit
echo 'Creating directory to copy files into...'
sudo mkdir eventkit
for file in api css images js DatabaseController.php index.php Installer.php Logger.php
do
	echo 'Copying '$file'...'
	sudo cp -r $file 'eventkit'
done
echo 'Creating zip file...'
sudo zip -r eventkit.zip eventkit
echo 'Cleaning up...'
sudo rm -rf eventkit
echo 'Done.'