iRail Summer of Code project
============================

<h1> TakeALookInside </h1>

<h2> Admin tool </h2>      

By navigating to this folder, you can login at the administrator tool. If you are logged in, it is possible to edit, delete, create and 
upload movies and buildings.

The login system uses a user table in the database system. The passwords are sha-1 encrypted!

<code>CREATE TABLE user (
    userId INT NOT NULL AUTO_INCREMENT,
    userName VARCHAR(20) NOT NULL,
    userRole TINYINT NOT NULL,
    userPassword VARCHAR(40) NOT NULL,
    PRIMARY KEY(userId)
);</code>

The userRole field can be used as extension for later purposes. Someone who can only create buildings, and another person who can do everything.

<h2> Creating TakeALookInside compatible QR-Codes </h2>

To create QR codes that can work with the application, you'll have to use the token that is provided in the admintool. Navigate to a website
that can create QR codes (for instance http://qrcode.kaywa.com/). Create the code as follows

- Type in the url of the server where everything is stored (http://tali.irail.be)
- After that, you'll have to place a #-sign (http://tali.irail.be#)
- And just paste the token, provided by our tool, after the # (http://tali.irail.be#MjAxMjA3MTdIb3RlbF9DbGVtbWVu)
- Generate the QR code and it should work.