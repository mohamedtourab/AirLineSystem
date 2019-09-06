/*These commented code for compatibility to submit of the code*/
CREATE DATABASE IF NOT EXISTS airlineDB;
USE airlineDB;

drop user if exists 'mohamed'@'localhost';
flush privileges;

CREATE USER 'mohamed'@'localhost' IDENTIFIED BY 'mohamed';
GRANT ALL PRIVILEGES ON *.* TO 'mohamed'@'localhost';
flush privileges;

CREATE TABLE IF NOT EXISTS Users
(
    userID       varchar(255) NOT NULL,
    userPassword varchar(255) NOT NULL,
    PRIMARY KEY (userID)
);

CREATE TABLE IF NOT EXISTS Seats
(
    seatRow     int                                  NOT NULL,
    seatColumn  char(1)                              NOT NULL,
    seatState   ENUM ('purchased','free','selected') NOT NULL,
    holdingUser varchar(255),
    CONSTRAINT RC_seat PRIMARY KEY (seatRow, seatColumn)
);
/*
INSERT INTO Users(userID, userPassword)
VALUES ('u1@p.it', 'p1');
INSERT INTO Users(userID, userPassword)
VALUES ('u2@p.it', 'p2');
*/