SELECT oi.Order_Item_ID, oi.OI_P_ID, p.Product_Name, oi.Quantity, oi.Price 
FROM order_items oi
JOIN Products p ON oi.OI_P_ID = p.Product_ID
WHERE oi.Oi_O_id = '$order_id';

INSERT INTO Returns (R_O_ID, Return_Date) VALUES ('$order_id', '$return_date');

SELECT Order_Item_ID, OI_P_ID, Quantity, Price
FROM order_items
WHERE Oi_O_id = '$order_id';

INSERT INTO Return_Items (RI_R_ID, RI_P_ID, Quantity, Price)
VALUES ('$return_id', '$product_id', '$quantity', '$price');

UPDATE Products SET Quantity = Quantity + $quantity WHERE Product_ID = '$product_id';

DELETE FROM order_items WHERE Order_Item_ID = $order_item_id;

SELECT OI_P_ID, Quantity, Price FROM order_items WHERE Order_Item_ID = $order_item_id;

INSERT INTO Return_Items (RI_R_ID, RI_P_ID, Quantity, Price)
VALUES ('$return_id', '$product_id', '$return_quantity', '$price');

UPDATE Products SET Quantity = Quantity + $return_quantity WHERE Product_ID = '$product_id';

UPDATE order_items SET Quantity = $remaining_quantity WHERE Order_Item_ID = $order_item_id;

DELETE FROM order_items WHERE Order_Item_ID = $order_item_id;

