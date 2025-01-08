SELECT * FROM Products WHERE Product_ID = '$product_id';

SELECT Category_ID FROM Category WHERE Category_ID = '$category_id';

SELECT Supplier_ID FROM Supplier WHERE Supplier_ID = '$supplier_id';

UPDATE Products SET 
Product_Name='$product_name', 
P_C_ID='$category_id', 
P_S_ID='$supplier_id', 
Quantity=$quantity, 
Price=$price 
WHERE Product_ID='$product_id';