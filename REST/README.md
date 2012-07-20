iRail Summer of Code project
============================

<h1> TakeALookInside </h1>
<h2> RESTful webservice </h2>


This is the webservice which provides the data for the app.

This data is stored in a database, the sql schema for the database can be found in the taliSQL_Schema.sql file.

It is possible to do posts and get requests using this RESTful webservice.

Example:
Get requests can look like this:
/REST/Building.json?select=buildingID;longitude;latitude,building.categoryID,category.name&join=category

Uses the buildingcontroller (based on the name Building), which means it will return buildings
Returns in JSON format (based on .json)
After the ?, different parameters can be given
The category table is equi-joined on the building table
This selects the buildingID, longitude, latitude, categoryID from the building table + the category name from the category table


For mor info about the possibilities of these restrictions, check the documentation in the code.

 