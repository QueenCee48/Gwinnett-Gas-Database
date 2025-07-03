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

    $date = $station = $grade = "";
    $date_err = $station_err = $grade_err = "";
    $success_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $hasError = false;

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

        if (!isset($_POST['station'])) {
            $station_err = "Please select the station.";
            $hasError = true;
        }
        else {
            $station = htmlspecialchars($_POST['station']);
        }

        if (!isset($_POST['grade'])) {
            $grade_err = "Please select the fuel grade.";
            $hasError = true;
        }
        else {
            $grade = htmlspecialchars($_POST['grade']);
        }

        if (!$hasError) {

                // User submitted and all fields are set
                include "connectDatabase.php";

                // Create short variable names
                $date = mysqli_real_escape_string($conn, $date);
                $stationid = mysqli_real_escape_string($conn, $station);
                $fuelgrade = mysqli_real_escape_string($conn, $grade);

                $startGal = $endGal = $pricePerGal = $supplierPrice = 0;
                $galSold = $revenue = $cost = $profit = 0;

                if ($grade == "Regular") {
                    $sql1  = "SELECT t.Reg_Start, t.Reg_End, stp.RegularPrice, sup.Regular ";
                    $sql1 .= "FROM tankvolumereport AS t ";
                    $sql1 .= "INNER JOIN dailystationprice AS stp ON stp.StationID = t.StationID AND stp.Date = t.Date ";
                    $sql1 .= "INNER JOIN dailysupplierprice AS sup ON sup.Date = t.Date ";

                    $result1 = $conn->query($sql1);

                    if ($result1->num_rows > 0) {
                        while ($row = $result1->fetch_assoc()) {
                            $startGal = $row['Reg_Start'];
                            $endGal = $row['Reg_End'];
                            $pricePerGal = $row['RegularPrice'];
                            $supplierPrice = $row['Regular'];
                            $galSold = $startGal - $endGal;
                            $revenue = $galSold * $pricePerGal;
                            $cost = $galSold * $supplierPrice;
                            $profit = $revenue - $cost;
                        }
                    }
                }
                elseif ($grade == "MidGrade") {
                    $sql1  = "SELECT t.Mid_Start, t.Mid_End, stp.MidGradePrice, sup.MidGrade ";
                    $sql1 .= "FROM tankvolumereport AS t ";
                    $sql1 .= "INNER JOIN dailystationprice AS stp ON stp.StationID = t.StationID AND stp.Date = t.Date ";
                    $sql1 .= "INNER JOIN dailysupplierprice AS sup ON sup.Date = t.Date ";

                    $result1 = $conn->query($sql1);

                    if ($result1->num_rows > 0) {
                        while ($row = $result1->fetch_assoc()) {
                            $startGal = $row['Mid_Start'];
                            $endGal = $row['Mid_End'];
                            $pricePerGal = $row['MidGradePrice'];
                            $supplierPrice = $row['MidGrade'];
                            $galSold = $startGal - $endGal;
                            $revenue = $galSold * $pricePerGal;
                            $cost = $galSold * $supplierPrice;
                            $profit = $revenue - $cost;
                        }
                    }
                }
                elseif ($grade == "Premium") {
                    $sql1  = "SELECT t.Prem_Start, t.Prem_End, stp.PremiumPrice, sup.Premium ";
                    $sql1 .= "FROM tankvolumereport AS t ";
                    $sql1 .= "INNER JOIN dailystationprice AS stp ON stp.StationID = t.StationID AND stp.Date = t.Date ";
                    $sql1 .= "INNER JOIN dailysupplierprice AS sup ON sup.Date = t.Date ";

                    $result1 = $conn->query($sql1);

                    if ($result1->num_rows > 0) {
                        while ($row = $result1->fetch_assoc()) {
                            $startGal = $row['Prem_Start'];
                            $endGal = $row['Prem_End'];
                            $pricePerGal = $row['PremiumPrice'];
                            $supplierPrice = $row['Premium'];
                            $galSold = $startGal - $endGal;
                            $revenue = $galSold * $pricePerGal;
                            $cost = $galSold * $supplierPrice;
                            $profit = $revenue - $cost;
                        }
                    }
                }

                // Validation query to ensure tankvolumereport has the entry
                $checkQuery1 = "SELECT 1 FROM tankvolumereport WHERE StationID = ? AND Date = ?";
                $checkStmt1 = $conn->prepare($checkQuery1);
                $checkStmt1->bind_param("is", $stationid, $date);
                $checkStmt1->execute();
                $checkResult1 = $checkStmt1->get_result();

                // Validation query to see if gasshiftreport has the entry
                $checkQuery2 = "SELECT 1 FROM gasshiftreport WHERE StationID = ? AND Date = ? AND Grade = ?";
                $checkStmt2 = $conn->prepare($checkQuery2);
                $checkStmt2->bind_param("iss", $stationid, $date, $fuelgrade);
                $checkStmt2->execute();
                $checkResult2 = $checkStmt2->get_result();

                if ($checkResult1->num_rows === 0) {
                    // No match found: do not insert
                    $success_msg = "
                        <div class='w3-margin w3-padding w3-red'>
                            <strong>No tank volume record found for Station $stationid on $date. Submission aborted.</strong><br>
                        </div>
                    ";
                } 
                elseif ($checkResult2->num_rows === 1) {
                    // Match found: do not insert
                    $success_msg = "
                        <div class='w3-margin w3-padding w3-red'>
                            <strong>A gas shift record already exists for $fuelgrade at Station $stationid on $date. Submission aborted.</strong><br>
                        </div>
                    ";
                }
                else {

                    $sql = "INSERT INTO gasshiftreport (Date, StationID, Grade, GallonSold, PricePerGal, SupplierPrice, Revenue, Cost, Profit) 
                        VALUES ('$date', '$stationid', '$fuelgrade', '$galSold', '$pricePerGal', '$supplierPrice', '$revenue', '$cost', '$profit') ";

                    if ($conn->query($sql) === TRUE) {
                        $success_msg = "
                        <div class='w3-margin w3-padding w3-pale-blue'>
                            <strong>New Gas Shift Report added successfully!</strong><br>
                            <br>
                            Date: $date<br>
                            Station ID: $stationid<br>
                            Fuel Grade: $fuelgrade<br>
                            Gallons Sold: $galSold<br>
                            Price Per Gallon: $pricePerGal<br>
                            Supplier Price: $supplierPrice<br>
                            Revenue: $revenue<br>
                            Cost: $cost<br>
                            Profit: $profit<br>
                        </div>";

                        $date = $station = $grade = "";
                    }

                    $conn->close();
                }
            }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCG - Add Gas Shift</title>
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
					<h4>Add Daily Gas Shift</h4>
				</div>
			</div>
		</div>

		<form action="addGasShift.php" method="POST" class="w3-margin">
            <fieldset class="w3-pale-blue">
                <div class="w3-margin-bottom">
                <label>Date:</label>
                <input type="text" class="w3-input w3-border <?= $date_err ? 'w3-border-red' : '' ?>" value="<?= $date ?>" name="date" placeholder="yyyy-mm-dd">
                <?php if ($date_err): ?><span class="w3-text-red"><?= $date_err ?></span><?php endif; ?>
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

                <div class="w3-margin-bottom">
                <label>Fuel Grade:</label>
                <select class="w3-select w3-border w3-white <?= $grade_err ? 'w3-border-red' : '' ?>" name="grade">
                    <option value="" disabled selected>Choose Fuel Grade</option>
                    <option value="Regular">Regular</option>
                    <option value="MidGrade">MidGrade</option>
                    <option value="Premium">Premium</option>
                </select>
                <?php if ($grade_err): ?><span class="w3-text-red"><?= $grade_err ?></span><?php endif; ?>
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