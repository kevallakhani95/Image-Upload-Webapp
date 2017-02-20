Image upload WebApp Instructions------------------------------------------------------------------------------------------------------

First, download Xampp and  MySql and set them up.
Then, activate Apache and MySql on Xampp control panel.
Then please copy the WebApp folder inside htdocs under Xampp folder. 

Then you have to import the pictures.sql file in your localhost/phpmyadmin. Once that is setup you can go to localhost/WebApp to access the application.
You can replace the name WebApp with any other name you want but make sure the name of the folder inside htdocs is the same.

Then you can login using the credentials Username=”abc”, Password=”xyz”.

Or you can create your own login by entering the credentials in the space Username and password. Just make sure to click the button “New User?..” after entering the details.
This will take you in and it will display a timeline. I have put some images to get an idea of the timeline. 
You can switch pages using the pagination tabs located at the bottom left of the page.
The tabs have been programmed to appear based on the situation.

You can go to My Account and see which images have been uploaded by this user name.
‘My Account’ also has pagination tabs.

When you go back to timeline, you can upload an image by selecting the image, giving a caption and submitting it.
It saves the image to the database. 

The database we are using here is the “pictures” database.
There are two tables:
Pics
Users
These tables are used to store information about the user and the pictures.

Please make sure to logout of the account else the session will remain on.
