# MySQL Script #
################

CREATE DATABASE TakeALookInside;

USE TakeALookInside;

CREATE TABLE Movie (
    movieID INT NOT NULL AUTO_INCREMENT,
    movie VARCHAR(50) NOT NULL,
    dateTime DATETIME NOT NULL,
    PRIMARY KEY(movieId)
);

CREATE TABLE Location (
    locationID INT NOT NULL AUTO_INCREMENT,
    longitude DECIMAL(10,7) NOT NULL,
    latitude DECIMAL(10,7) NOT NULL,
    PRIMARY KEY(locationID)
);

CREATE TABLE Category (
    categoryID INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    PRIMARY KEY(categoryID)
);

CREATE TABLE Building (
    buildingID INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    picture VARCHAR(50) NOT NULL,
    infoLink VARCHAR(100),
    description TEXT,
    openingHours TEXT,
    mustSee INT DEFAULT 0,
    seen INT DEFAULT 0,
    movieID INT NOT NULL,
    locationID INT NOT NULL,
    categoryID INT,
    PRIMARY KEY(buildingID),
    FOREIGN KEY(movieID) REFERENCES Movie(movieID) ON DELETE RESTRICT,
    FOREIGN KEY(locationID) REFERENCES Location(locationID) ON DELETE RESTRICT,
    FOREIGN KEY(categoryID) REFERENCES Category(categoryID) ON DELETE RESTRICT
);
