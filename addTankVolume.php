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

    $date = $station = $regStart = $regEnd = $midStart = $midEnd = $premStart = $premEnd = "";
    $date_err = $station_err = $regStart_err = $regEnd_err = $midStart_err = $midEnd_err = $premStart_err = $premEnd_err = "";
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

        if (empty(trim($_POST['regularS']))) {
            $regStart_err = "Please enter gallons.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['regularS'])) {
            $regStart_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $regStart = htmlspecialchars($_POST['regularS']);
        }

        if (empty(trim($_POST['regularE']))) {
            $regEnd_err = "Please enter gallons.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['regularE'])) {
            $regEnd_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $regEnd = htmlspecialchars($_POST['regularE']);
        }

        if (empty(trim($_POST['midgradeS']))) {
            $midStart_err = "Please enter gallons.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['midgradeS'])) {
            $midStart_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $midStart = htmlspecialchars($_POST['midgradeS']);
        }

        if (empty(trim($_POST['midgradeE']))) {
            $midEnd_err = "Please enter gallons.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['midgradeE'])) {
            $midEnd_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $midEnd = htmlspecialchars($_POST['midgradeE']);
        }

        if (empty(trim($_POST['premiumS']))) {
            $premStart_err = "Please enter gallons.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['premiumS'])) {
            $premStart_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $premStart = htmlspecialchars($_POST['premiumS']);
        }

        if (empty(trim($_POST['premiumE']))) {
            $premEnd_err = "Please enter gallons.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{1,11}?$/", $_POST['premiumE'])) {
            $premEnd_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $premEnd = htmlspecialchars($_POST['premiumE']);
        }

        if (!$hasError) {

                // User submitted and all fields are set
                include "connectDatabase.php";

                // Create short variable names
                $date = mysqli_real_escape_string($conn, $date);
                $stationid = mysqli_real_escape_string($conn, $station);
                $regularStart = mysqli_real_escape_string($conn, $regStart);
                $regularEnd = mysqli_real_escape_string($conn, $regEnd);
                $midgradeStart = mysqli_real_escape_string($conn, $midStart);
                $midgradeEnd = mysqli_real_escape_string($conn, $midEnd);
                $premiumStart = mysqli_real_escape_string($conn, $premStart);
                $premiumEnd = mysqli_real_escape_string($conn, $premEnd);

                $sql = "INSERT INTO tankvolumereport (Date, StationID, Reg_Start, Reg_End, Mid_Start, Mid_End, Prem_Start, Prem_End) 
                    VALUES ('$date', '$stationid', '$regularStart', '$regularEnd', '$midgradeStart', '$midgradeEnd', '$premiumStart', '$premiumEnd') ";

                if ($conn->query($sql) === TRUE) {
                    $success_msg = "
                    <div class='w3-margin w3-padding w3-pale-blue'>
                        <strong>New Daily Tank Volume added successfully!</strong><br>
                        <br>
                        Date: $date<br>
                        Station ID: $stationid<br>
                        Starting Regular: $regularStart gal<br>
                        Ending Regular: $regularEnd gal<br>
                        Starting MidGrade: $midgradeStart gal<br>
                        Ending MidGrade: $midgradeEnd gal<br>
                        Starting Premium: $premiumStart gal<br>
                        Ending Premium: $premiumEnd gal<br>
                    </div>";

                    $date = $station = $regStart = $regEnd = $midStart = $midEnd = $premStart = $premEnd = "";
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
    <title>GCG - Add Tank Volume</title>
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
					<h4>Add Daily Tank Volume</h4>
				</div>
			</div>
		</div>

		<form action="addTankVolume.php" method="POST" class="w3-margin">
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
                <label>Starting Regular (gal):</label>
                <input type="text" class="w3-input w3-border <?= $regStart_err ? 'w3-border-red' : '' ?>" value="<?= $regStart ?>" name="regularS">
                <?php if ($regStart_err): ?><span class="w3-text-red"><?= $regStart_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Ending Regular (gal):</label>
                <input type="text" class="w3-input w3-border <?= $regEnd_err ? 'w3-border-red' : '' ?>" value="<?= $regEnd ?>" name="regularE">
                <?php if ($regEnd_err): ?><span class="w3-text-red"><?= $regEnd_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Starting MidGrade (gal):</label>
                <input type="text" class="w3-input w3-border <?= $midStart_err ? 'w3-border-red' : '' ?>" value="<?= $midStart ?>" name="midgradeS">
                <?php if ($midStart_err): ?><span class="w3-text-red"><?= $midStart_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Ending MidGrade (gal):</label>
                <input type="text" class="w3-input w3-border <?= $midEnd_err ? 'w3-border-red' : '' ?>" value="<?= $midEnd ?>" name="midgradeE">
                <?php if ($midEnd_err): ?><span class="w3-text-red"><?= $midEnd_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Starting Premium (gal):</label>
                <input type="text" class="w3-input w3-border <?= $premStart_err ? 'w3-border-red' : '' ?>" value="<?= $premStart ?>" name="premiumS">
                <?php if ($premStart_err): ?><span class="w3-text-red"><?= $premStart_err ?></span><?php endif; ?>
                </div>

                <div>
                <label>Ending Premium (gal):</label>
                <input type="text" class="w3-input w3-border <?= $premEnd_err ? 'w3-border-red' : '' ?>" value="<?= $premEnd ?>" name="premiumE">
                <?php if ($premEnd_err): ?><span class="w3-text-red"><?= $premEnd_err ?></span><?php endif; ?>
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