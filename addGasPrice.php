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

    $date = $station = $reg = $mid = $prem = "";
    $date_err = $station_err = $reg_err = $mid_err = $prem_err = "";
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

        if (empty(trim($_POST['regular']))) {
            $reg_err = "Please enter a price.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['regular'])) {
            $reg_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $reg = htmlspecialchars($_POST['regular']);
        }

        if (empty(trim($_POST['midgrade']))) {
            $mid_err = "Please enter a price.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['midgrade'])) {
            $mid_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $mid = htmlspecialchars($_POST['midgrade']);
        }

        if (empty(trim($_POST['premium']))) {
            $prem_err = "Please enter a price.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['premium'])) {
            $prem_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $prem = htmlspecialchars($_POST['premium']);
        }

        if (!$hasError) {

                // User submitted and all fields are set
                include "connectDatabase.php";

                // Create short variable names
                $date = mysqli_real_escape_string($conn, $date);
                $stationid = mysqli_real_escape_string($conn, $station);
                $regular = mysqli_real_escape_string($conn, $reg);
                $midgrade = mysqli_real_escape_string($conn, $mid);
                $premium = mysqli_real_escape_string($conn, $prem);

                $sql = "INSERT INTO dailystationprice (Date, StationID, RegularPrice, MidGradePrice, PremiumPrice) 
                    VALUES ('$date', '$stationid', '$regular', '$midgrade', '$premium') ";

                if ($conn->query($sql) === TRUE) {
                    $success_msg = "
                    <div class='w3-margin w3-padding w3-pale-blue'>
                        <strong>New Daily Supplier Price added successfully!</strong><br>
                        <br>
                        Date: $date<br>
                        Station ID: $stationid<br>
                        Regular: $regular<br>
                        MidGrade: $midgrade<br>
                        Premium: $premium<br>
                    </div>";

                    $date = $station = $reg = $mid = $prem = "";
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
    <title>GCG - Add Gas Price</title>
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
					<h4>Add Daily Gas Price</h4>
				</div>
			</div>
		</div>

		<form action="addGasPrice.php" method="POST" class="w3-margin">
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
                <label>Regular:</label>
                <input type="text" class="w3-input w3-border <?= $reg_err ? 'w3-border-red' : '' ?>" value="<?= $reg ?>" name="regular">
                <?php if ($reg_err): ?><span class="w3-text-red"><?= $reg_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>MidGrade:</label>
                <input type="text" class="w3-input w3-border <?= $mid_err ? 'w3-border-red' : '' ?>" value="<?= $mid ?>" name="midgrade">
                <?php if ($mid_err): ?><span class="w3-text-red"><?= $mid_err ?></span><?php endif; ?>
                </div>

                <div>
                <label>Premium:</label>
                <input type="text" class="w3-input w3-border <?= $prem_err ? 'w3-border-red' : '' ?>" value="<?= $prem ?>" name="premium">
                <?php if ($prem_err): ?><span class="w3-text-red"><?= $prem_err ?></span><?php endif; ?>
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