SELECT Price, Quantity FROM Products WHERE Product_ID = '$product_id';


SELECT Customer_ID FROM Customer WHERE Customer_ID = '$customer_id';


INSERT INTO Orders (Order_Date, Total_Amount, O_C_ID, O_E_ID) 
VALUES ('$order_date', $total_amount, " . ($customer_id !== null ? "'$customer_id'" : "NULL") . ", $employee_id);
        
SELECT Price, Quantity FROM Products WHERE Product_ID = '$product_id';


INSERT INTO order_items (Oi_O_id, OI_P_ID, Quantity, Price) 
VALUES ($order_id, '$product_id', $quantity, $price);


UPDATE Products SET Quantity = $new_quantity WHERE Product_ID = '$product_id';


