
CREATE TABLE Users(
userID varchar(255) NOT NULL,
userPassword varchar(20) NOT NULL,
PRIMARY KEY(userID)
);

CREATE TABLE Seats(
seatRow int NOT NULL,
seatColumn char(1) NOT NULL,
seatState ENUM('purchased','free','selected') NOT NULL,
holdingUser varchar(255),
CONSTRAINT RC_seat PRIMARY KEY (seatRow,seatColumn)
);

-- Insert rows into table 'TableName'
INSERT INTO Users(userID, userPassword) VALUES('mohamedmamdouh','1234');
INSERT INTO Users(userID, userPassword) VALUES('mamdouhtourab','78910');
INSERT INTO Seats(seatRow, seatColumn,seatState) VALUES('1','A','free');
INSERT INTO Seats(seatRow, seatColumn,seatState) VALUES('2','A','free');
INSERT INTO Seats(seatRow, seatColumn,seatState) VALUES('3','A','free');
INSERT INTO Seats(seatRow, seatColumn,seatState) VALUES('4','A','free');

