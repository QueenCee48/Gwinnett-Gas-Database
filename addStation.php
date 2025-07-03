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

    $sname = $addy = $city = $zip = $frechar = $envifee = "";
    $sname_err = $addy_err = $city_err = $zip_err = $frechar_err = $envifee_err = "";
    $success_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $hasError = false;

        //station name
        if (empty(trim($_POST['sname']))) {
            $sname_err = "Please enter the station name.";
            $hasError = true;
        }
        else {
            $sname = htmlspecialchars($_POST['sname']);
        }

        //station address
        if (empty(trim($_POST['addy']))) {
            $addy_err = "Please enter the address.";
            $hasError = true;
        }
        else {
            $addy = htmlspecialchars($_POST['addy']);
        }

        //station city
        if (empty(trim($_POST['city']))) {
            $city_err = "Please enter the city.";
            $hasError = true;
        }
        else {
            $city = htmlspecialchars($_POST['city']);
        }

        //station zip
        if (empty(trim($_POST['zip']))) {
            $zip_err = "Please enter the zipcode.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d{5}?$/", $_POST['zip'])) {
            $zip_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $zip = htmlspecialchars($_POST['zip']);
        }

        //freight charge
        if (empty(trim($_POST['frechar']))) {
            $frechar_err = "Please enter a price.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['frechar'])) {
            $frechar_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $frechar = htmlspecialchars($_POST['frechar']);
        }

        //environmental fee
        if (empty(trim($_POST['envifee']))) {
            $envifee_err = "Please enter a price.";
            $hasError = true;
        }
        elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['envifee'])) {
            $envifee_err = "Must be a valid number.";
            $hasError = true;
        }
        else {
            $envifee = htmlspecialchars($_POST['envifee']);
        }

        if (!$hasError) {

                // User submitted and all fields are set
                include "connectDatabase.php";

                // Create short variable names
                $SName = mysqli_real_escape_string($conn, $sname);
                $Addy = mysqli_real_escape_string($conn, $addy);
                $City = mysqli_real_escape_string($conn, $city);
                $Zip = mysqli_real_escape_string($conn, $zip);
                $FreChar = mysqli_real_escape_string($conn, $frechar);
                $EnviFee = mysqli_real_escape_string($conn, $envifee);

                $sql = "INSERT INTO station (StationName, Address, City, ZipCode, FreightCharge, EnvironmentalFee) 
                    VALUES ('$SName', '$Addy', '$City', '$Zip', '$FreChar', '$EnviFee') ";

                if ($conn->query($sql) === TRUE) {
                    $success_msg = "
                    <div class='w3-margin w3-padding w3-pale-blue'>
                        <strong>New Station added successfully!</strong><br>
                        <br>
                        Station Name: $SName<br>
                        Address: $Addy<br>
                        City: $City<br>
                        Zip: $Zip<br>
                        Freight Charge: $FreChar<br>
                        Environmental Fee: $EnviFee<br>
                    </div>";

                    $sname = $addy = $city = $zip = $frechar = $envifee = "";
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
    <title>GCG - Add Station</title>
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
					<h4>Add Station</h4>
				</div>
			</div>
		</div>

        <!-- $sname = $addy = $city = $zip = $frechar = $envifee = ""; -->

		<form action="addStation.php" method="POST" class="w3-margin">
            <fieldset class="w3-pale-blue">
                <div class="w3-margin-bottom">
                <label>Station Name:</label>
                <input type="text" class="w3-input w3-border <?= $sname_err ? 'w3-border-red' : '' ?>" value="<?= $sname ?>" name="sname">
                <?php if ($sname_err): ?><span class="w3-text-red"><?= $sname_err ?></span><?php endif; ?>
                </div>
                
                <div class="w3-margin-bottom">
                <label>Street Address:</label>
                <input type="text" class="w3-input w3-border <?= $addy_err ? 'w3-border-red' : '' ?>" value="<?= $addy ?>" name="addy">
                <?php if ($addy_err): ?><span class="w3-text-red"><?= $addy_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>City:</label>
                <input type="text" class="w3-input w3-border <?= $city_err ? 'w3-border-red' : '' ?>" value="<?= $city ?>" name="city">
                <?php if ($city_err): ?><span class="w3-text-red"><?= $city_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Zip Code:</label>
                <input type="text" class="w3-input w3-border <?= $zip_err ? 'w3-border-red' : '' ?>" value="<?= $zip ?>" name="zip">
                <?php if ($zip_err): ?><span class="w3-text-red"><?= $zip_err ?></span><?php endif; ?>
                </div>

                <div class="w3-margin-bottom">
                <label>Freight Charge:</label>
                <input type="text" class="w3-input w3-border <?= $frechar_err ? 'w3-border-red' : '' ?>" value="<?= $frechar ?>" name="frechar">
                <?php if ($frechar_err): ?><span class="w3-text-red"><?= $frechar_err ?></span><?php endif; ?>
                </div>

                <div>
                <label>Environmental Fee:</label>
                <input type="text" class="w3-input w3-border <?= $envifee_err ? 'w3-border-red' : '' ?>" value="<?= $envifee ?>" name="envifee">
                <?php if ($envifee_err): ?><span class="w3-text-red"><?= $envifee_err ?></span><?php endif; ?>
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