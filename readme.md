d6 Technical Assessment
==============

The backend has been written in PHP, and the frontend in html, javascript. The database is mySQL.


Getting the project up and running
------------
<b>Database</b>
The database is located in ~\database\d6.sql

<b>Backend</b>
Add a virtual host that points to the location of the public project folder. ~\backend\public
Add the correct database connection details in ~\backend\config.php

<b>Frontend</b>
Change the ~\frontend\assets\js\config.js file to point to the correct API url
Double click ~\frontend\index.html file to open the web page