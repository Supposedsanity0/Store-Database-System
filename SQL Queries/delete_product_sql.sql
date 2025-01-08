DELETE FROM Customer WHERE C_O_ID IN (SELECT Order_id FROM Orders WHERE O_P_ID = '$product_id');

DELETE FROM Orders WHERE O_P_ID = '$product_id';

DELETE FROM Products WHERE Product_ID = '$product_id';

