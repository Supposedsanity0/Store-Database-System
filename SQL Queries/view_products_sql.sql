SELECT Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, Barcode 
FROM Products 
WHERE Barcode LIKE '%$searchQuery%' 
OR Product_ID LIKE '%$searchQuery%' 
OR Product_Name LIKE '%$searchQuery%';

SELECT Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, Barcode FROM Products;

