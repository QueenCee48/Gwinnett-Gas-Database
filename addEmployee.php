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

    $fName = $lName = $station = $wage = "";
    $fName_err = $lName_err = $station_err = $wage_err = "";
    $success_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $hasError = false;

        if (empty(trim($_POST['fName']))) {
            $fName_err = "Please enter the first name.";
            $hasError = true;
        }
        else {
            $fName = htmlspecialchars($_POST['fName']);
        }

        if (empty(trim($_POST['lName']))) {
            $lName_err = "Please enter the last name.";
            $hasError = true;
        }
        else {
            $lName = htmlspecialchars($_POST['lName']);
        }

        if (!isset($_POST['station'])) {
            $station_err = "Please select the station.";
            $hasError = true;
        }
        else {
            $station = htmlspecialchars($_POST['station']);
        }

        if (empty(trim($_POST['wage']))) {
            $wage_err = "Please enter the hourly wage.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['wage'])) {
            $wage_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $wage = htmlspecialchars($_POST['wage']);
        }

        if (!$hasError) {

                // User submitted and all fields are set
                include "connectDatabase.php";

                // Create short variable names
                $firstName = mysqli_real_escape_string($conn, $fName);
                $lastName = mysqli_real_escape_string($conn, $lName);
                $stationid = mysqli_real_escape_string($conn, $station);
                $hourlywage = mysqli_real_escape_string($conn, $wage);

                $sql = "INSERT INTO employee (FirstName, LastName, StationID, HourlyWage) 
                    VALUES ('$firstName', '$lastName', '$stationid', '$hourlywage') ";

                if ($conn->query($sql) === TRUE) {
                    $success_msg = "
                    <div class='w3-margin w3-padding w3-pale-blue'>
                        <strong>New Employee added successfully!</strong><br>
                        <br>
                        First Name: $firstName<br>
                        Last Name: $lastName<br>
                        Station ID: $stationid<br>
                        Hourly Wage: $hourlywage<br>
                    </div>";

                    $fName = $lName = $station = $wage = "";
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
    <title>GCG - Add Employee</title>
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
					<h4>Add Employee</h4>
				</div>
			</div>
		</div>

		<form action="addEmployee.php" method="POST" class="w3-margin">
            <fieldset class="w3-pale-blue">
                <div class="w3-margin-bottom">
                <label>First Name:</label>
                <input type="text" class="w3-input w3-border <?= $fName_err ? 'w3-border-red' : '' ?>" value="<?= $fName ?>" name="fName">
                <?php if ($fName_err): ?><span class="w3-text-red"><?= $fName_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Last Name:</label>
                <input type="text" class="w3-input w3-border <?= $lName_err ? 'w3-border-red' : '' ?>" value="<?= $lName ?>" name="lName">
                <?php if ($lName_err): ?><span class="w3-text-red"><?= $lName_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Station:</label>
                <select class="w3-select w3-border w3-white <?= $station_err ? 'w3-border-red' : '' ?>" name="station">
                    <option value="" disabled selected>Choose Station</option>
                    <?php
                        include "connectDatabase.php";

                        $sql  = "SELECT StationID, StationName ";
                        $sql .= "FROM station ";
                        $sql .= "ORDER BY StationID ";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) 
                            while ($row = $result->fetch_assoc()) {
                                $stationid = $row['StationID'];
                                $stationName = $row['StationName'];

                                echo "<option value='$stationid'>$stationid - $stationName</option>";
                            }

                        $conn->close();
                    ?>
                </select>
                <?php if ($station_err): ?><span class="w3-text-red"><?= $station_err ?></span><?php endif; ?>
                </div>

                <div>
                <label>Hourly Wage:</label>
                <input type="text" class="w3-input w3-border <?= $wage_err ? 'w3-border-red' : '' ?>" value="<?= $wage ?>" name="wage">
                <?php if ($wage_err): ?><span class="w3-text-red"><?= $wage_err ?></span><?php endif; ?>
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