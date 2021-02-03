CREATE DATABASE addressbook;

CREATE TABLE Contact(
	ContactId INT NOT NULL AUTO_INCREMENT UNIQUE, 
	FirstName VARCHAR(45) NOT NULL, 
	LastName VARCHAR(45) NOT NULL,
	PhoneNumber VARCHAR(45) NOT NULL UNIQUE, 
	JobTitle VARCHAR(45), 
	Address VARCHAR(100),
	Email VARCHAR(45), 
	PRIMARY KEY (ContactId)
);

CREATE TABLE Image (
	ImageId INT NOT NULL AUTO_INCREMENT UNIQUE, 
	ImagePath VARCHAR(300) NOT NULL, 
    	ContactId INT NOT NULL UNIQUE,
	PRIMARY KEY (ImageId),
	FOREIGN KEY (ContactId) REFERENCES Contact(ContactId)
);

CREATE TABLE Relationship(
	RelationshipId INT NOT NULL AUTO_INCREMENT UNIQUE, 
	RelationshipType VARCHAR(45) NOT NULL,
	FromContactId INT NOT NULL,
	ToContactId INT NOT NULL,
	PRIMARY KEY(RelationshipId),
	FOREIGN KEY(FromContactId) REFERENCES Contact(ContactId),
	FOREIGN KEY(ToContactId) REFERENCES Contact(ContactId)
);


INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('John', 'Hawat', '78986123', 'CEO', 'Lebanon', 'johnhawat@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Nabil', 'Bechara', '71456963', 'teacher', 'Lebanon', 'nabilbechara@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Souad', 'Nabbout', '70777999', 'assistant', 'Lebanon', 'souadnabbout@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Dalida', 'Fares', '78666333', 'trainer', 'Lebanon', 'dalidafares@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Lama', 'Sarkis', '78774558', 'engineer', 'Lebanon', 'lamasarkis@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Randa', 'Semaan', '71441441', 'designer', 'Lebanon', 'randasemaan@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Youssef', 'Sarkis', '78856658', 'architect', 'Lebanon', 'youssefsarkis@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Jad', 'Dergham', '70123321', 'scientist', 'Lebanon', 'jaddergham@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Miled', 'Yaacoub', '71123321', 'manager', 'Lebanon', 'miledyaacoub@yahoo.com');

INSERT INTO Contact (FirstName, LastName, PhoneNumber, JobTitle, Address, Email)
VALUES ('Adib', 'Abdelnour', '76123321', 'bar tender', 'Lebanon', 'adibabdelnour@yahoo.com');


INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 1);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 2);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 3);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 4);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 5);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 6);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 7);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 8);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 9);

INSERT INTO Image (ImagePath, ContactId)
VALUES ('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-contact-outline-512.png', 10);
