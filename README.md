iRail Summer of Code project
============================

<h1> TakeALookInside </h1>




Purpose of this project:

The project is design from the perspective of a passer-by in the city of Ghent who sees a building and thinks:
"I wonder how this building would look on the inside". But the building is closed or is never open to the public.

Starting from this idea, we designed an app which lets people 'look inside' different buildings in Ghent.

They can watch a short movie (30s-60s) of how the building looks at the inside by scanning a QR-code.
After the user watched the movie, he can add it to his favorites and replay it later. The users also gets to a map
of all the different locations of the buildings where they can look inside. This map can be filtered by different
categories. If the user selects a location, a popup shows some details of the location and the can get routing information
to the target location.
In the home screen, people can see the most favorited movies, their own favorites, the movies they tagged as 'look later'
and the movies they've already seen.
There is also a build-in scanner to scan the QR-codes in front of the participating buildings.


This project consists of 5 major parts:
- The app itself: 
    This is the site or www folder of the HTML5 app, this can be found in the app folder.
    It is used in Phonegap and should be added to the www folders of the different iOS, Android, ... projects.
- A webservice which provides the data
- The mobile website version of the app, without video functionallity but provides a link to the google play store
- A desktop version of the website. This is used to show general information about the app if you watch the site on a PC.
- An administration application to add/edit/remove different buildings and movies to the database
    
In detail information can be found in readme files in the app folder & in the controllers folder.


The structure of the project (except for the app folder) is based on .P, this is an open-source framework made by Sam Verschueren <sam@iRail.be>, it can be found here: https://github.com/SamVerschueren/.P


Developed during #iSoc12 by:

Code:
- Benoot Lieven     <lieven.benoot@iRail.be>    
- Verschueren Sam   <sam@iRail.be>              

Design:
- Dierck Nicolas    <nicolas@iRail.be>          