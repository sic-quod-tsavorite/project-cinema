DROP DATABASE IF EXISTS CinemaDB;
CREATE DATABASE CinemaDB;
USE CinemaDB;

CREATE TABLE Actors (
    actorID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE Tag (
    tagID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(255),
    short_description VARCHAR(255)
) ENGINE=InnoDB;


CREATE TABLE Movies (
    movieID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    length INT,
    description VARCHAR(255),
    poster VARCHAR(255),
    heroimg VARCHAR(255),
    trailer VARCHAR(255),
    released DATE,
    director VARCHAR(255),
    actor INT,
    tag INT,
    FOREIGN KEY (actor) REFERENCES Actors(actorID),
    FOREIGN KEY (tag) REFERENCES Tag(tagID)
) ENGINE=InnoDB;

CREATE TABLE ActorRole (
    actor_roleID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    actorID INT NOT NULL,
    movieID INT NOT NULL,
    role VARCHAR(255),
    FOREIGN KEY (actorID) REFERENCES Actors(actorID),
    FOREIGN KEY (movieID) REFERENCES Movies(movieID)
) ENGINE=InnoDB;

CREATE TABLE UserAccounts (
    userID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    accountRank INT DEFAULT 1,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- DATA --

-- Actors data:
insert into Actors (actorID, first_name, last_name) values (NULL, 'Joseph', 'Gordon-Levitt');

-- Tag data:
insert into Tag (tagID, name, short_description) values (NULL, 'Hand-Drawn', 'Hand-drawn animation, also known as traditional animation, is a technique in which each frame is created by hand. It involves the process of drawing individual images, known as frames or cels, by skilled animators and then photographing or filming them in sequence to create the illusion of motion.');

-- Movies data:
insert into Movies (movieID, title, length, description, poster, heroimg, trailer, released, director, actor, tag) values (NULL, 'Treasure Planet', NULL, 'Jim Hawkins is a teenager who finds the map of a great treasure hidden by a space pirate. Together with some friends, he sets off in a large spaceship, shaped like a caravel, on his quest.', NULL, NULL, NULL, '2002-11-29', 'Ron Clements', 1, 1);

-- ActorRole data:
insert into ActorRole (actor_roleID, actorID, movieID, role) values (NULL, 1, 1, 'Jim Hawkins');

-- UserAccounts data (password = username):
insert into UserAccounts (userID, accountRank, username, password) values (NULL, 0, 'admin', '$2y$15$nm1n/tNyw6.Os0QL5IsQ9.8twjXF1kbbGU.N23.MlWaHzTaNK50py');
insert into UserAccounts (userID, username, password) values (NULL, 'user', '$2y$15$2BR2./R3hUQQwRdIu8ZJmug0NPKmaNgS4cG3GiXHs7GO4MtV0qJvu');

-- VIEWS --

-- List all actors and their roles in movies:
CREATE VIEW MovieActors AS
SELECT m.title AS movie_title, ar.role AS movie_role, a.first_name, a.last_name
FROM Movies m
JOIN ActorRole ar ON m.movieID = ar.movieID
JOIN Actors a ON ar.actorID = a.actorID
ORDER BY m.title;