iRail Summer of Code project
============================

<h1> TakeALookInside </h1>
<h2> Controllers </h2>

<h3> Admin Tool - AdminController & LogonController</h3>

By navigating to this folder, you can login at the administrator tool. If you are logged in, it is possible to edit, delete, create and 
upload movies and buildings.

<h4> Login </h4>

The login system uses a user table in the database system. The passwords are sha-1 encrypted!

<code>CREATE TABLE user (
    userId INT NOT NULL AUTO_INCREMENT,
    userName VARCHAR(20) NOT NULL,
    userRole TINYINT NOT NULL,
    userPassword VARCHAR(40) NOT NULL,
    PRIMARY KEY(userId)
);</code>

The userRole field can be used as extension for later purposes. Someone who can only create buildings, and another person who can do everything.

<h4> Admin page </h4>

You can see an overview of all buildings in the database. It is possible to perform actions like: 
 - Generate QR-code
 - Edit a building
 - Delete a building
 - Add a building

In the edit page it is possible to edit all atributes of a building including:
 - Uploading videos & adding videos to a building.
 - Using a website to find longitude & latitude of different location.
 
 
<h3> Getting & manipulation the data - BuildingController, CategoryController & DeviceController </h3>

 - Buildingcontroller: gets & manipulates all data about buildings:
    - top query for top must sees & insert/remove must sees
    - buildings per category 
    - ...

    