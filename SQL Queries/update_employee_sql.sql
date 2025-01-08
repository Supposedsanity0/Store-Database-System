SELECT * FROM Employee WHERE Employee_ID = $employee_id;

UPDATE Employee 
SET Employee_name = '$employee_name', Role = '$role', Join_date = '$join_date', Email = '$email'
WHERE Employee_ID = $employee_id;