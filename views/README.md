iRail Summer of Code project
============================

<h1> TakeALookInside </h1>

<h2> Views </h2>

These views are used to visualize the different parts:
 - Admin panel
 - Desktop website
 - Mobile website

<h3> The mobile website </h3>

A lot of the code of the mobile website (in the ../content/scripts/custom/map.js file) is the same as in the app directory but the mobile version has less features and all code which calls native methods is removed.

It's used as a mobile webapp, it provides support for scanning QR-codes for people who don't have the application installed.

If a user scans a QR-code that belongs to this app, it shows the map and the popup of the scanned building. They cannot 
watch the movie but a downloadlink to the app is provided, in order to be able to download the app and watch the movie.

In the app all functionality is available, in the webapp, most of it is disable.
It is not possible to:
- watch movies
- like/favorite
- home screen with top must sees, favorites, seen, look later