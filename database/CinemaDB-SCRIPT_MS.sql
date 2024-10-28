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
    tagType INT DEFAULT 2,
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
    isNews INT,
    now_upcoming INT
) ENGINE=InnoDB;

CREATE TABLE ActorRole (
    actor_roleID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    actorID INT NOT NULL,
    movieID INT NOT NULL,
    role VARCHAR(255),
    FOREIGN KEY (actorID) REFERENCES Actors(actorID),
    FOREIGN KEY (movieID) REFERENCES Movies(movieID)
) ENGINE=InnoDB;

CREATE TABLE MovieTags (
    movie_tagID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    movieID INT NOT NULL,
    tagID INT NOT NULL,
    FOREIGN KEY (movieID) REFERENCES Movies(movieID),
    FOREIGN KEY (tagID) REFERENCES Tag(tagID)
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
insert into Actors (actorID, first_name, last_name) values (NULL, 'Emma', 'Thompson');

-- Tag data:
insert into Tag (tagID, name, tagType, short_description) values (NULL, 'Hand-Drawn', 1, 'Hand-drawn animation, also known as traditional animation, is a technique in which each frame is created by hand. It involves the process of drawing individual images, known as frames or cels, by skilled animators and then photographing or filming them in sequence to create the illusion of motion.');

-- Movies data:
insert into Movies (movieID, title, length, description, poster, heroimg, trailer, released, director, isNews, now_upcoming) values (NULL, 'Treasure Planet', 95, 'Jim Hawkins is a teenager who finds the map of a great treasure hidden by a space pirate. Together with some friends, he sets off in a large spaceship, shaped like a caravel, on his quest.', 'includes/assets/uploads/posters/treasure-planet.jpg', 'includes/assets/uploads/heroimgs/treasure-planet-bg.jpg', 'includes/assets/uploads/trailers/TreasurePlanetTrailer.mp4', '2002-11-29', 'Ron Clements', 1, 1);
insert into Movies (movieID, title, length, description, poster, heroimg, trailer, released, director, isNews, now_upcoming) values (NULL, 'The Nightmare Before Christmas', 76, 'Jack Skellington, king of Halloween Town, discovers Christmas Town, but his attempts to bring Christmas to his home causes confusion.', 'includes/assets/uploads/posters/tnbc.jpg', 'includes/assets/uploads/heroimgs/Nightmare-Before-Christmas-2-release-date-story-details.jpg', 'includes/assets/uploads/trailers/TheNightmareBeforeChristmasTrailer.mp4', '1994-12-02', 'Henry Selick', 1, 2);
insert into Movies (movieID, title, length, description, poster, heroimg, trailer, released, director, isNews, now_upcoming) values (NULL, 'Shrek', 90, 'A mean lord exiles fairytale creatures to the swamp of a grumpy ogre, who must go on a quest and rescue a princess for the lord in order to get his land back.', 'includes/assets/uploads/posters/shrek.jpg', 'includes/assets/uploads/heroimgs/shrek-heroimg.jpg', 'includes/assets/uploads/trailers/ShrekTrailer.mp4', '2001-09-07', 'Andrew Adamson & Vicky Jenson', 1, 2);
insert into Movies (movieID, title, length, description, poster, heroimg, trailer, released, director, isNews, now_upcoming) values (NULL, 'The Witcher: Nightmare of the Wolf', 83, 'Escaping from poverty to become a witcher, Vesemir slays monsters for coin and glory, but when a new menace rises, he must face the demons of his past.', 'includes/assets/uploads/posters/twnotw.jpg', 'includes/assets/uploads/heroimgs/wolf-heroimg.jpg', 'includes/assets/uploads/trailers/wolfTrailer.mp4', '2021-08-23', 'Kwang Il Han', 1, 1);

-- ActorRole data:
insert into ActorRole (actor_roleID, actorID, movieID, role) values (NULL, 1, 1, 'Jim Hawkins');
insert into ActorRole (actor_roleID, actorID, movieID, role) values (NULL, 2, 1, 'Captain Amelia');

-- MovieTags data:
insert into MovieTags (movie_tagID, movieID, tagID) values (NULL, 1, 1);

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