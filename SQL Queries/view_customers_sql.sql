UPDATE Customer 
SET Customer_Name = '$customer_name', Phone_Number = '$phone_number', Email = '$email' 
WHERE Customer_ID = '$customer_id';

SELECT * FROM Customer;