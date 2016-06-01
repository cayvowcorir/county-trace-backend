# leader-project-track-backend
Backend for the leader-project-track

##Prerequisites
1. PHP ^5.6
2. Composer

##Installation
1. Clone the repository into a folder
2. Run 'Composer Install'

##Firing things up
Navigate to the root folder and run 'php -S 0.0.0.0:8080 -t public public/index.php' from your terminal

##Db Migration
###Assumptions:
-Your Sql Server access credentials are assumed to be: username: 'root', password: ''. The port is assumed to be '3306' and host: 'localhost'
-if this is not the case, change the values in '/src/Migrations/Migration.php'

1. Create a database and name it as 'countytrace
2. Execute 'php vendor/bin/phinx migrate -c config-phinx.php' in the terminal from the root of the application
