CREATE TABLE Category (
    Category_ID varchar(50) PRIMARY KEY,
    Category_Name varchar(100)
);

CREATE TABLE Supplier (
    Supplier_ID varchar(50) PRIMARY KEY,
    Supplier_Name varchar(100),
    Supplier_email varchar(100)
);

CREATE TABLE Products (
    Product_ID varchar(50) PRIMARY KEY,
    Product_Name varchar(100),
    Barcode varchar(100),
    P_C_ID varchar(50),
    P_S_ID varchar(50),
    Quantity int,
    Price int,
    FOREIGN KEY (P_C_ID) REFERENCES Category(Category_ID),
    FOREIGN KEY (P_S_ID) REFERENCES Supplier(Supplier_ID)
);

CREATE TABLE Orders (
    Order_id bigint auto_increment not null  PRIMARY KEY,
    Order_Date DATE,
    O_C_ID int,
    O_E_ID int,
    Total_Amount float not null,
	FOREIGN KEY (O_C_ID) REFERENCES Customer(Customer_ID),
    FOREIGN KEY (O_E_ID) REFERENCES Employee(Employee_ID)
);

CREATE TABLE order_items (
    Order_Item_ID INT AUTO_INCREMENT PRIMARY KEY,
    Oi_O_id bigint,
    OI_P_ID VARCHAR(50),
    Quantity INT not null,
    Price float not null,
    FOREIGN KEY (Oi_O_id) REFERENCES Orders(Order_id),
    FOREIGN KEY (OI_P_ID) REFERENCES Products(Product_ID)
);

CREATE TABLE Customer (
    Customer_ID int Auto_Increment PRIMARY KEY,
    Customer_Name varchar(100),
    Phone_Number varchar(15) unique,
    Email varchar(100)
);

CREATE TABLE Employee (
    Employee_ID int auto_increment PRIMARY KEY,
    Employee_name varchar(50),
    Role varchar(100),
    Join_date Date,
    Email varchar(100)
);
-- Table to store return information
CREATE TABLE Returns (
    Return_ID INT AUTO_INCREMENT PRIMARY KEY,
    R_O_ID BIGINT NOT NULL,
    Return_Date DATE NOT NULL,
    FOREIGN KEY (R_O_ID) REFERENCES Orders(Order_id)
);

-- Table to store returned items
CREATE TABLE Return_Items (
    Return_Item_ID INT AUTO_INCREMENT PRIMARY KEY,
    RI_R_ID INT NOT NULL,
    RI_P_ID VARCHAR(50) NOT NULL,
    Quantity INT NOT NULL,
    Price FLOAT NOT NULL,
    FOREIGN KEY (RI_R_ID) REFERENCES Returns(Return_ID),
    FOREIGN KEY (RI_P_ID) REFERENCES Products(Product_ID)
);