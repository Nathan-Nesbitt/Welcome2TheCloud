CREATE TABLE customer (
    customerId          INT NOT NULL AUTO_INCREMENT,
    firstName           VARCHAR(40),
    lastName            VARCHAR(40),
    email               VARCHAR(50),
    phonenum            VARCHAR(20),
    address             VARCHAR(50),
    city                VARCHAR(40),
    state               VARCHAR(20),
    postalCode          VARCHAR(20),
    country             VARCHAR(40),
    userid              VARCHAR(20) UNIQUE,
    password            CHAR(255),
    PRIMARY KEY (customerId)
);

CREATE TABLE tokens (
    tokenId INT NOT NULL AUTO_INCREMENT,
    userid VARCHAR(20) NOT NULL,
    token VARCHAR(512),
    PRIMARY KEY (tokenID)
);

CREATE TABLE paymentmethod (
    paymentMethodId     INT NOT NULL AUTO_INCREMENT,
    paymentType         VARCHAR(20),
    paymentNumber       VARCHAR(30),
    paymentExpiryDate   DATE,
    customerId          INT,
    PRIMARY KEY (paymentMethodId),
    FOREIGN KEY (customerId) REFERENCES customer(customerid)
        ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE ordersummary (
    orderId             INT NOT NULL AUTO_INCREMENT,
    orderDate           DATETIME,
    totalAmount         DECIMAL(10,2),
    shiptoAddress       VARCHAR(50),
    shiptoCity          VARCHAR(40),
    shiptoState         VARCHAR(20),
    shiptoPostalCode    VARCHAR(20),
    shiptoCountry       VARCHAR(40),
    customerId          INT,
    PRIMARY KEY (orderId),
    FOREIGN KEY (customerId) REFERENCES customer(customerid)
        ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE category (
    categoryId          INT NOT NULL AUTO_INCREMENT,
    categoryName        VARCHAR(50),    
    PRIMARY KEY (categoryId)
);

CREATE TABLE product (
    productId           INT NOT NULL AUTO_INCREMENT,
    productName         VARCHAR(40),
    productPrice        DECIMAL(10,2),
    productImageURL     TEXT,
    productImage        LONGBLOB,
    productDesc         VARCHAR(1000),
    categoryId          INT,
    PRIMARY KEY (productId),
    FOREIGN KEY (categoryId) REFERENCES category(categoryId)
);

CREATE TABLE orderproduct (
    orderId             INT,
    productId           INT,
    quantity            INT,
    price               DECIMAL(10,2),  
    PRIMARY KEY (orderId, productId),
    FOREIGN KEY (orderId) REFERENCES ordersummary(orderId)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE incart (
    orderId             INT,
    productId           INT,
    quantity            INT,
    price               DECIMAL(10,2),  
    PRIMARY KEY (orderId, productId),
    FOREIGN KEY (orderId) REFERENCES ordersummary(orderId)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE warehouse (
    warehouseId         INT NOT NULL AUTO_INCREMENT,
    warehouseName       VARCHAR(30),    
    PRIMARY KEY (warehouseId)
);

CREATE TABLE shipment (
    shipmentId          INT NOT NULL AUTO_INCREMENT,
    shipmentDate        DATETIME,   
    shipmentDesc        VARCHAR(100),   
    warehouseId         INT, 
    PRIMARY KEY (shipmentId),
    FOREIGN KEY (warehouseId) REFERENCES warehouse(warehouseId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE productinventory ( 
    productId           INT,
    warehouseId         INT,
    quantity            INT,
    price               DECIMAL(10,2),  
    PRIMARY KEY (productId, warehouseId),   
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (warehouseId) REFERENCES warehouse(warehouseId)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE review (
    reviewId            INT NOT NULL AUTO_INCREMENT,
    reviewRating        INT,
    reviewDate          DATETIME,   
    customerId          INT,
    productId           INT,
    reviewComment       VARCHAR(1000),          
    PRIMARY KEY (reviewId),
    FOREIGN KEY (customerId) REFERENCES customer(customerId)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES product(productId)
        ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO category(categoryName) VALUES ('High-level');
INSERT INTO category(categoryName) VALUES ('Mid-level');
INSERT INTO category(categoryName) VALUES ('Low-level');


INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Cirrus', 1, 'Cirrus is one of the most common types of clouds that can be seen at any time of the year. They’re thin and wispy with a silky sheen appearance.', 23.00, 'images/Cirrus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Cirrocumulus', 1, 'Cirrocumulus clouds exhibit features from both cumulus and cirrus clouds but should not be confused with altocumulus clouds. While the two can look similar, cirrocumulus does not have shading and some parts of altocumulus are darker than the rest. Cirrocumulus cloud comes after cirrus cloud during warm frontal system.', 12.00, 'images/Cirrocumulus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Cirrostratus', 2, 'Cirrostratus clouds have a sheet-like appearance that can look like a curly blanket covering the sky. They’re quite translucent which makes it easy for the sun or the moon to peer through. Their color varies from light gray to white and the fibrous bands can vary widely in thickness. Purely white cirrostratus clouds signify these have stored misture, indicating the presence of a warm frontal system.', 24.00, 'images/Cirrostratus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Altocumulus', 2, 'Altocumulus clouds form at a lower altitude so they’re largely made of water droplets though they may retain ice crystals when forming higher up. They usually appear between lower stratus clouds and higher cirrus clouds, and normally precede altostratus when a warm frontal system is advancing.', 9.00, 'images/Altocumulus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Altostratus', 2, 'They’re uniformly gray, smooth, and mostly featureless which is why they’re sometimes called ‘boring clouds’. You’ll commonly see this types of clouds in an advancing warm frontal system, preceding nimbostratus clouds.', 24.00, 'images/Altostratus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Nimbostratus', 2, 'The name Nimbostratus comes from the Latin words nimbus which means “rain” and stratus for “spread out”. These gloomy clouds are the heavy rain bearers out there forming thick and dark layers of clouds that can completely block out the sun. Though they belong to the middle-level category, they may sometimes descend to lower altitudes.', 22.00, 'images/Nimbostratus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Stratus', 3, 'Stratus clouds are composed of thin layers of clouds covering a large area of the sky. This is simply mist or fog when it forms close to the ground.',12.00, 'images/Stratus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Cumulus', 3, 'The most recognizable out of all the types of clouds. These adorable ‘piles of cotton’ form a large mass with a well-defined rounded edge.',13.00, 'images/Cumulus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Cumulonimbus', 3, 'Cumulonimbus is fluffy and white like cumulus but the cloud formations are far larger. It’s a vertical developing type of cloud whose base grows from one to up to eight kilometers, hence it’s commonly called a tower cloud.',13.00, 'images/Cumulonimbus.jpg');
INSERT product(productName, categoryId, productDesc, productPrice, productImageURL) VALUES ('Stratocumulus', 3, 'Stratocumulus looks like a thick white blanket of stretched out cotton.',14.00, 'images/Stratocumulus.jpg');

INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Arnold', 'Anderson', 'a.anderson@gmail.com', '204-111-2222', '103 AnyWhere Street', 'Winnipeg', 'MB', 'R3X 45T', 'Canada', 'arnold' , '$2y$11$azpjt5DO6BW.HxXUkw/bruI/BPmwBCDppr2DDcP1EOEHHIFZbtDcW');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Bobby', 'Brown', 'bobby.brown@hotmail.ca', '572-342-8911', '222 Bush Avenue', 'Boston', 'MA', '22222', 'United States', 'bobby' , '$2y$11$azpjt5DO6BW.HxXUkw/bruI/BPmwBCDppr2DDcP1EOEHHIFZbtDcW');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Candace', 'Cole', 'cole@charity.org', '333-444-5555', '333 Central Crescent', 'Chicago', 'IL', '33333', 'United States', 'candace' , '$2y$11$azpjt5DO6BW.HxXUkw/bruI/BPmwBCDppr2DDcP1EOEHHIFZbtDcW');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Darren', 'Doe', 'oe@doe.com', '250-807-2222', '444 Dover Lane', 'Kelowna', 'BC', 'V1V 2X9', 'Canada', 'darren' , '$2y$11$azpjt5DO6BW.HxXUkw/bruI/BPmwBCDppr2DDcP1EOEHHIFZbtDcW');
INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES ('Elizabeth', 'Elliott', 'engel@uiowa.edu', '555-666-7777', '555 Everwood Street', 'Iowa City', 'IA', '52241', 'United States', 'beth' , '$2y$11$azpjt5DO6BW.HxXUkw/bruI/BPmwBCDppr2DDcP1EOEHHIFZbtDcW');


INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (1, '2019-10-15 10:25:55', 91.70);
SET @newOrderId = LAST_INSERT_ID();

INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 1, 1, 18);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 5, 2, 21.35);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 10, 1, 31);

INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (2, '2019-10-16 18:00:00', 106.75);
SET @newOrderId = LAST_INSERT_ID();
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 5, 5, 21.35);

INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (3, '2019-10-15 3:30:22', 140);
SET @newOrderId = LAST_INSERT_ID();
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 6, 2, 25);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 7, 3, 30);

INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (2, '2019-10-17 05:45:11', 327.85);
SET @newOrderId = LAST_INSERT_ID();
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 3, 4, 10);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 8, 3, 40);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 7, 3, 23.25);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 2, 2, 21.05);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 1, 4, 14);

INSERT INTO ordersummary (customerId, orderDate, totalAmount) VALUES (5, '2019-10-15 10:25:55', 277.40);
SET @newOrderId = LAST_INSERT_ID();
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 5, 4, 21.35);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 9, 2, 81);
INSERT INTO orderproduct (orderId, productId, quantity, price) VALUES (@newOrderId, 2, 3, 10);