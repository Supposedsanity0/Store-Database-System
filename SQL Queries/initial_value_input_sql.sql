INSERT INTO Category (Category_ID, Category_Name) VALUES
('FG', 'Figures'),
('BO', 'Books'),
('AC', 'accessories'),
('GA', 'Games'),
('PO', 'Posters');

INSERT INTO Supplier (Supplier_ID, Supplier_Name, Supplier_email) VALUES
('BA', 'Bandai', 'Bandai@gmail.com'),
('FU', 'Funko', 'Funko@gmail.com'),
('IS', 'Iron Studios', 'IronST@gmail.com'),
('KB', 'Kotobukiya', 'Koto@gmail.com'),
('SE', 'Sega', 'Sega@gmail.com');

INSERT INTO Products (Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, barcode) VALUES
('AC-BA-24157', 'Keychain - Grendizer', 'AC', 'BA', 12, 150, 44270707224157),
('FG-BA-85673', 'Bandai - banpresto - luffy', 'FG', 'BA', 18, 1995, 32414753285673),
('FG-FU-12345', 'Funko POP! - Anime - Goku', 'FG', 'FU', 38, 795, 98713413612345),
('FG-IS-16485', 'Iron Studios - Batman VS Joker', 'FG', 'IS', 1, 65000, 12446456416485),
('GA-SE-12636', 'Sega - Sonic Adventures 2', 'GA', 'SE', 2, 2995, 12432554212636);

INSERT INTO Customer (Customer_ID, C_O_ID, Customer_Name, Phone_Number, Email) VALUES
(1, 'ORD1', 'John Doe', '1234567890', 'john.doe@example.com'),
(2, 'ORD2', 'Jane Smith', '9876543210', 'jane.smith@example.com'),
(3, 'ORD3', 'Alice Johnson', '5555555555', 'alice.johnson@example.com'),
(4, 'ORD4', 'Bob Brown', '1112223333', 'bob.brown@example.com'),
(5, 'ORD5', 'Charlie Davis', '4445556666', 'charlie.davis@example.com'),
(6, 'ORD6', 'Eva Green', '7778889999', 'eva.green@example.com'),
(7, 'ORD7', 'Frank White', '2223334444', 'frank.white@example.com'),
(8, 'ORD8', 'Grace Black', '6667778888', 'grace.black@example.com'),
(9, 'ORD9', 'Henry Wilson', '8889990000', 'henry.wilson@example.com'),
(10, 'ORD10', 'Ivy Lee', '9990001111', 'ivy.lee@example.com');

INSERT INTO Employee (Employee_ID, Role, Join_date, Email, employee_name) VALUES
(101, 'Manager', '2020-01-15', 'manager@example.com', 'Maha'),
(102, 'Sales Associate', '2021-06-20', 'sales@example.com', 'Youssef'),
(103, 'Support Staff', '2022-03-10', 'support@example.com', 'Mohamed'),
(104, 'Accountant', '2021-09-25', 'accountant@example.com', 'Gaber'),
(105, 'IT Specialist', '2023-02-15', 'it@example.com', 'Mamdouh');