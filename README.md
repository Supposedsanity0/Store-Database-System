# Store-Database-System

this is a simple website that allows you to add/edit/and sometime's delete the following attributes:
employee's
customers
products
categories
suppliers
it also allows you to access the products table and choose from the available items and places them in a cart.
once all the items are selected the user can enter the cart and shown will be the products selected with all its information and the grand total.
checkout can then be clicked to complete the order with all the info given in the previous page and is then placed in the orders tab showing all the order information.
more advanced functions like returns and downloading excel files to show total sales and returns.


this project uses PHP and MYSQL with the the XAMPP application to connect the PHP files to the local host.
there are a total of 9 tables created using MYSQL WORKBENCH.
there are a total of 27 PHP files that all connect to Index.php.
21 of the 27 PHP contain SQL queries which are all contained in the SQL queries folder, with each file containing the specific queries used for each PHP file with the corresponding name.
15 files are connected directly to the Index.PHP file and can be accessed from the page and vis versa.
also contained is a Entity Relation Diagram and a Relational Table that shows the connections between the different tables that are shown in the database


explanation of each file:

general notes: all files (except index) contain a line of code at the top that connects the PHP files to the database to be able to use the sql queries to retrieve or submit data from the many tables of the database.

Add Category:

allows you to input both a Category ID and name and inserts them into the category table after checking that the category ID is unique and doesn't clash with any existing category.

Add Customers:

takes customer info which consists of: name, phone number, and email and automaticlly assigns an auto incremented Customer ID and inserts it into the customers table which can then be called later for selecting a customer when placing an order.

Add Employee:

asks for employee name, role, join date and email and adds them to the database with an autoincremented id that starts from 101, which can then be used later to add a sales representitve to any order made. 

Add product:

prompts the input of product details including: Product ID, Product Name, Category and Supplier IDs, Quantity and Price which then adds the information to the products table, giving an error message if the Product ID that was entered already exists or if the Category and Supplier IDs don't exist.

