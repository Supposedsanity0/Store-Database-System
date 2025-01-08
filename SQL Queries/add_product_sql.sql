SELECT Category_ID FROM Category WHERE Category_ID = '$category_id';

SELECT Supplier_ID FROM Supplier WHERE Supplier_ID = '$supplier_id';

INSERT INTO Products (Product_ID, Product_Name, P_C_ID, P_S_ID, Quantity, Price, Barcode)
VALUES ('$product_id', '$product_name', '$category_id', '$supplier_id', $quantity, $price, '$barcode');