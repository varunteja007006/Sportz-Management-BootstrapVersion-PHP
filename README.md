# Sports Management Website ⚽
This is about creating your own webpage using HTML, CSS, PHP, AJAX. Its just a fun and simple project that you can use for learning.

First of CSS is uesd to style the HTML page. The following is the list of Cascading Style sheets I had to make for this project:
1. add_events.css
2. homepage.css
3. students_page.css
4. style.css

Also attached few avatar.png and avatar2.svg. These are used for login and sign up.

Now coming to the HTML files:
1. homepage.html
2. contact.html
3. aboutus.html
4. chooseprofile.html

> chooseprofile helps you to choose the login as user or admin.

> When you check the aboutus there is a xml related script enclosed in between the tags <script>.... </script>. You can delete those lines if you do not want them else you can modify the xml file (xmlfile.xml).

Php related files are:
1. config.php 
2. logout.php

> Config file allows you to connect to your "phpmyadmin" server. The server name is localhost, you will login as "root" and if its password protected give you password else just leave it like this "". Now you give your database name, in this case it is "login".

> logout file allows you to disonnect from the server.

Now some php files are divided between users and admin.

Admin has access to following files:
1. admin_signup.php
2. add_events.php
3. results.php
4. Adminlogin.php

> admin_signup allows the admin of the webpage to create his username and password.

> Adminlogin allows you to login into the admin account to proceed into two webpages add_events and results.

> add_events allows you to add any sports event and its details as an admin.

> results allows you to add results of the event as an admin.

Users interact with the following php files:
1. signup.php
2. studentlogin.php
3. ongoing_events.php
4. events_registered.php
5. display_results.php

> signup allows new users to signup into your webpage and studentlogin allows user to login.

> ongoing_events allow user to check the list of events that the admin added into database and whichever event you book will be shown in events_registered and you can search for results in display_results.

Finally gethint.php just provides suggestions while you type in search box of display_results.


References:

@W3schools 

@CodeWithHarry (youtube) 

@mmtuts (youtube)

@learnWebCoding (youtube)

@Thapa Technical (youtube)

***************************************************************************************
Improvements to be made:
* Add search bars 
* check the redirection of already logged in user or not logged in user
* Check for sorting the files and change the ref links

***************************************************************************************
Features lacking in the template
student > events registered > 
2. add search bar(events registered) -- search result display

student > results
1. add search bar to search events+players(username) -- search result display
2. Get results associated with username

admin > dashboard>
1. edit the events and results
2. add results to events that have no results 

admin > addevents == (partially done)
1. datepicker
2. timepicker features

***************************************************************************************
ADD ON:
1. detail view page for student login
2. take firstname, lastname
3. Active and inactive
4. Update events, results in admin login

*****************************************************************************************
Things to note:
1. Results can be picked from drop down or by filling the form.	
