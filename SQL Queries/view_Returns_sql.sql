SELECT r.Return_ID, r.R_O_ID AS Order_ID, r.Return_Date, ri.RI_P_ID AS Product_ID, ri.Quantity, ri.Price, p.Product_Name
FROM Returns r
JOIN Return_Items ri ON r.Return_ID = ri.RI_R_ID
JOIN Products p ON ri.RI_P_ID = p.Product_ID
ORDER BY r.Return_Date DESC;

