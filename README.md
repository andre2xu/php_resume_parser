# PHP Resume Parser &nbsp; ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) ![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)<br>

***\*\* THIS IS NOT A LIBRARY OR PACKAGE! \*\****<br><br>
It is a web app intended for small-to-medium sized businesses and for those seeking to learn advanced programming skills, i.e. it is a commercial product in the form of a codebase.
<br><br>

<ins>The app requires Docker</ins> to be installed and running on a host machine. It is recommended to get *Docker Desktop* as well to make usage easier.
<br><br>

## Free Version Installation
First, open a terminal and check if you have Docker installed. The command below should return a version number.
```
docker --version
```
<br>

Then pick a folder in your machine and clone this repository inside it.
```
cd Desktop

git clone https://github.com/andre2xu/php_resume_parser.git
```
<br>

After the cloning/download is complete, run the project's Docker compose file. This will install all the packages required and create the web app (check in Docker Desktop).
```
cd php_resume_parser

docker compose up -d
```
<br>

The final step is to run the project's *init.php* file to set up the MySQL database. This is done using *Docker exec*. Open up Docker Desktop and find the project's container called 'php_resume_parser' (make sure it is running). Then click on 'server-1' and go to the exec tab. You should see a terminal. Type `php init.php` and wait until you are able to type again.
<br>

The installation should now be complete. You can visit the web app by searching `http://localhost:5001/index.php/login` on a browser.
<br><br>

## Premium Version
The premium version is available for purchase on my website [INSERT LINK HERE]. The installation instructions are there as well.

<br><br><br><br>

## FREE VERSION (this repo)
![Free Version Dashboard](./demo/free%20version%20dashboard.png)
<br><br><br><br>

## PREMIUM VERSION
![Premium Version Dashboard 1](./demo/premium%20version%20dashboard%201.png)
![Premium Version Dashboard 2](./demo/premium%20version%20dashboard%202.png)
![Premium Version Dashboard 3](./demo/premium%20version%20dashboard%203.png)