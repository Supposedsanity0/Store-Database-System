/* the following sql commands run when the download excel sheet button is pressed */
SELECT o.Order_id, o.Order_Date, o.Total_Amount, c.Customer_Name, e.Employee_name 
FROM Orders o
JOIN Customer c ON o.O_C_ID = c.Customer_ID
JOIN Employee e ON o.O_E_ID = e.Employee_ID
ORDER BY o.Order_Date DESC;

SELECT SUM(ri.Quantity * ri.Price) AS Returned_Amount 
FROM Return_Items ri
JOIN Returns r ON ri.RI_R_ID = r.Return_ID
WHERE r.R_O_ID = '$order_id';
/* end of excel sheet sql commands */

/* the following is run normally to collect data on the main page */
SELECT o.Order_id, o.Order_Date, o.Total_Amount, c.Customer_Name, e.Employee_name 
FROM Orders o
JOIN Customer c ON o.O_C_ID = c.Customer_ID
JOIN Employee e ON o.O_E_ID = e.Employee_ID
ORDER BY o.Order_Date DESC;