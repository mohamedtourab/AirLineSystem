CREATE DATABASE IF NOT EXISTS airlinedatabase;
USE airlinedatabase;

flush privileges;

CREATE USER 'mohamed'@'localhost' IDENTIFIED BY 'mohamed';
GRANT ALL PRIVILEGES ON *.* TO 'mohamed'@'localhost';
flush privileges;

CREATE TABLE IF NOT EXISTS Users
(
    userID       varchar(40) NOT NULL,
    userPassword varchar(20) NOT NULL,
    PRIMARY KEY (userID)
);

CREATE TABLE IF NOT EXISTS Seats
(
    seatRow     int                                  NOT NULL,
    seatColumn  char(1)                              NOT NULL,
    seatState   ENUM ('purchased','free','selected') NOT NULL,
    holdingUser varchar(40),
    CONSTRAINT RC_seat PRIMARY KEY (seatRow, seatColumn)
);

INSERT INTO Users(userID, userPassword)
VALUES ('u1@p.it', '1234');
INSERT INTO Users(userID, userPassword)
VALUES ('u2@p.it', '1234');
