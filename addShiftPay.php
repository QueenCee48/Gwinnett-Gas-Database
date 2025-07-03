<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $_SESSION = array();
            session_destroy();
            header("location: login.php");
            exit;
        }
    }

    $employee = $date = $hours = "";
    $employee_err = $date_err = $hours_err = "";
    $success_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $hasError = false;

        if (!isset($_POST['employee'])) {
            $employee_err = "Please select the employee.";
            $hasError = true;
        }
        else {
            $employee = htmlspecialchars($_POST['employee']);
        }

        if (empty(trim($_POST['date']))) {
            $date_err = "Please enter the date.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $_POST['date'])) {
            $date_err = "Date must be in yyyy-mm-dd format.";
            $hasError = true;
        }
        else {
            $date = htmlspecialchars($_POST['date']);
        }

        if (empty(trim($_POST['hours']))) {
            $hours_err = "Please enter the hours worked.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['hours'])) {
            $hours_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $hours = htmlspecialchars($_POST['hours']);
        }

        if (!$hasError) {

                // User submitted and all fields are set
                include "connectDatabase.php";

                // Create short variable names
                $employeeid = mysqli_real_escape_string($conn, $employee);
                $date = mysqli_real_escape_string($conn, $date);
                $hoursworked = mysqli_real_escape_string($conn, $hours);

                $sql = "INSERT INTO employeeshift (EmployeeID, Date, HoursWorked) 
                    VALUES ('$employeeid', '$date', '$hoursworked') ";

                if ($conn->query($sql) === TRUE) {
                    $success_msg = "
                    <div class='w3-margin w3-padding w3-pale-blue'>
                        <strong>New Employee Shift & Pay added successfully!</strong><br>
                        <br>
                        Employee ID: $employeeid<br>
                        Date: $date<br>
                        Hours Worked: $hoursworked<br>
                    </div>";

                    $employee = $date = $hours = "";
                }

                $conn->close();
            }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCG - Add Shift & Pay</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-cyan.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="w3-theme-d5">
    <div class="w3-theme-d5">
		<header class="w3-container w3-center">
            <form action="home.php" method="POST">
                <input type="submit" name="logout" value="Logout" class="w3-button w3-margin-top w3-right w3-black">
            </form>
            
			<h1><a href="home.php">Gwinnett County Gas</a></h1>
		</header>
        
		<?php
			include 'mainMenu.php';
		?>
		
		<div class="w3-container w3-center">
			<div class="w3-card-4" style="width:100%">
				<div class="w3-container w3-center w3-theme-d3">
					<h4>Add Employee Shift & Pay</h4>
				</div>
			</div>
		</div>

		<form action="addShiftPay.php" method="POST" class="w3-margin">
            <fieldset class="w3-pale-blue">

                <div class="w3-margin-bottom">
                <label>Employee:</label>
                <select class="w3-select w3-border w3-white <?= $employee_err ? 'w3-border-red' : '' ?>" name="employee">
                    <option value="" disabled selected>Choose Employee</option>
                    <?php
                        include "connectDatabase.php";

                        $sql  = "SELECT EmployeeID, FirstName, LastName ";
                        $sql .= "FROM employee ";
                        $sql .= "ORDER BY EmployeeID ";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) 
                            while ($row = $result->fetch_assoc()) {
                                $employeeid = $row['EmployeeID'];
                                $fName = $row['FirstName'];
                                $lName = $row['LastName'];

                                echo "<option value='$employeeid'>$employeeid - $fName $lName</option>";
                            }

                        $conn->close();
                    ?>
                </select>
                <?php if ($employee_err): ?><span class="w3-text-red"><?= $employee_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Date:</label>
                <input type="text" class="w3-input w3-border <?= $date_err ? 'w3-border-red' : '' ?>" value="<?= $date ?>" name="date" placeholder="yyyy-mm-dd">
                <?php if ($date_err): ?><span class="w3-text-red"><?= $date_err ?></span><?php endif; ?>
                </div>

                <div>
                <label>Hours Worked:</label>
                <input type="text" class="w3-input w3-border <?= $hours_err ? 'w3-border-red' : '' ?>" value="<?= $hours ?>" name="hours">
                <?php if ($hours_err): ?><span class="w3-text-red"><?= $hours_err ?></span><?php endif; ?>
                </div>
            </fieldset>

            <input type="submit" name="submit" value="Add" class="w3-button w3-margin-top w3-black">
        </form>

        <?= $success_msg ?>

		<footer class="w3-container w3-center">
			<p id="signature">Made by Ciera Baucham</p>
		</footer>
	</div>	
</body>
</html>