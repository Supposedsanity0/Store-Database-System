# Store-Database-System

Important Notice: this project requires PHPSpreadsheet Repo by Compresor to use the download excel button in the Orders Page.

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
below you will find the database's ERD, Relational Table and Mapping.



![ORM](https://github.com/user-attachments/assets/72813eb8-877d-4c14-a9c6-b7a9a8483cf6)

![ERD](https://github.com/user-attachments/assets/4d6d907f-aa67-4e32-a78f-0407f64b87ee)

![Relational Table](https://github.com/user-attachments/assets/1107109a-d18c-48d4-bf3f-b5e1bc9f290f)



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

Add Supplier:

Asks User for Supplier ID and Supplier Name and adds the inputed info into the Supplier Table.

Add to Cart:

a hidden PHP within the project that can only be used in the View Product Page that allows you to add any items of any quantity within the given range of the product to a cart that is created by the file.

Cart:

Stores all items that were selected from the view products page and allows you to either clear the cart or finalize the order using the checkout file.

Checkout:

A hidden PHP file that adds all the items in the cart into the Order Items Table and links the Order Items to the Orders Table with an Auto incremented ID afterwhich it clears the cart and decreases the Quantity of the Product by the amount that was ordered.

DB_Connection:

Provides all PHP files with the Database Information to be able to connnect with the database and retrive or edit information as needed.

Delete Product:

Prompts the user for a Product ID which is then removed from the database if the Product ID is correct.

Index:

A simple PHP file that connects all the main pages to each other through it.

Orders:

A page that allows you to view all previous Orders and download an excel file using PHPSpreadsheets to show all orders, returns, difference and total sales.

Returns:

Allows you to Partially or Fully Return any Order with existing items with a valid Order ID, noting that if no items are selected the entire order will be returned.

Update Employee:

Allows you to edit Employee Information Including: Name, Role, Email, and Phone Number.

Update Product:

Changes Product Info which consists of: Product Name, Category ID, Supplier ID, Quantity and Price after giving a valid product ID.

Update Supplier:

Allows you to change Supplier Name and Email.

All View Files:

Shows you all Rows saved in the respective table of each page while occasionally allowing you to edit or delete the row instead of having a specific page to take the action.
