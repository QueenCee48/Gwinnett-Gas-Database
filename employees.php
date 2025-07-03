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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCG - Employees</title>
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
					<h4>Employees</h4>
				</div>
			</div>
		</div>

		<div class="w3-margin w3-padding">
            <?php
                include "connectDatabase.php";

                $sql  = "SELECT e.EmployeeID, e.FirstName, e.LastName, s.StationName, e.HourlyWage  ";
                $sql .= "FROM employee AS e ";
                $sql .= "INNER JOIN station AS s ON e.StationID = s.StationID ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='5' class='tableHeader'>Employees
                                    <a href='addEmployee.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>EmployeeID</th>";
                    echo "      <th>FirstName</th>";
                    echo "      <th>LastName</th>";
                    echo "      <th>StationName</th>";
                    echo "      <th>HourlyWage</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['EmployeeID'] . "</td>";
                        echo " <td>" . $row['FirstName'] . "</td>";
                        echo " <td>" . $row['LastName'] . "</td>";
                        echo " <td>" . $row['StationName'] . "</td>";
                        echo " <td>" . $row['HourlyWage'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }

                else
                    echo "0 results<br>";

                $conn->close();
            ?>
        </div>

		<div class="w3-margin w3-padding">
            <?php
                include "connectDatabase.php";

                $sql  = "SELECT e.FirstName, e.LastName, es.Date, e.HourlyWage, es.HoursWorked, (es.HoursWorked * e.HourlyWage) AS Pay ";
                $sql .= "FROM employeeshift AS es ";
                $sql .= "INNER JOIN employee AS e ON es.EmployeeID = e.EmployeeID ";
                $sql .= "ORDER BY es.Date ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='5' class='tableHeader'>Shifts & Pay
                                    <a href='addShiftPay.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>FirstName</th>";
                    echo "      <th>LastName</th>";
                    echo "      <th>Date</th>";
                    echo "      <th>HoursWorked</th>";
                    echo "      <th>Pay</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        // $hours = $row['HoursWorked'];
                        // $wage = $row['HourlyWage'];
                        // $pay = $hours * $wage;

                        echo "<tr>";
                        echo " <td>" . $row['FirstName'] . "</td>";
                        echo " <td>" . $row['LastName'] . "</td>";
                        echo " <td>" . $row['Date'] . "</td>";
                        echo " <td>" . $row['HoursWorked'] . "</td>";
                        // echo " <td>" . number_format($pay, 2) . "</td>";
                        echo " <td>" . number_format($row['Pay'], 2) . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }

                else
                    echo "0 results<br>";

                $conn->close();
            ?>
        </div>

		<footer class="w3-container w3-center">
			<p id="signature">Made by Ciera Baucham</p>
		</footer>
	</div>	
</body>
</html>