# Notes for CP476 Project

## Setting this up for the Mac
- Day 1: setting up Apache and PHP for Mac
	- https://pimylifeup.com/install-php-on-macos/ very useful resource
	- https://getgrav.org/blog/macos-ventura-apache-multiple-php-versions
	- use `sudo apachectl -k restart` to restart the server also.
	- installed mysql also, used Warp AI's help to get the .bashrc file PATH access
		- use `mysql -u root -p` to access the database instance
	- MySQL commands:
		- https://www.prisma.io/dataguide/mysql/create-and-delete-databases-and-tables
- Day 2: Jul 8th, 2023
	- When working with CSS use `Cmd + Shift + R` to reload all stylesheets otherwise changes won't register on the browser.
	- Code for login system comes from: https://codeshack.io/secure-login-system-php-mysql/
- Day 3: Jul 9th, 2023
	- fixed the issue where data could not be put into the tables.
	- Now two major systems work: login/logout and putting data into the sql tables
	- Prerequisite: a cp476 db must be set up + an accounts table with a username [test, test].
-