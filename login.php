<?php
    session_start();

    // check if the user is already logged in. If yes, then redirect to home page.
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header("location: home.php");
        exit;
    }
    
    $username = $password = "";
    $username_err = $password_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $hasError = false;

        if (empty(trim($_POST['username']))) {
            $username_err = "Please enter username.";
            $hasError = true;
        }
        else {
            $username = htmlspecialchars($_POST['username']);
        }

        if (empty(trim($_POST['password']))) {
            $password_err = "Please enter your password.";
            $hasError = true;
        }
        else {
            $password = htmlspecialchars($_POST['password']);
        }

        if (empty($username_err) && empty($password_err)) {
            session_start();

            // store data in session variables
            $_SESSION['loggedin'] = true;
            // $_SESSION['username'] = $username;

            // redirect to welcome page
            header("location: home.php");
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
    <title>GCG - Login</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-cyan.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="w3-theme-d5">
    <div class="w3-theme-d5">
		<header class="w3-container w3-center">
			<h1>Gwinnett County Gas</h1>
		</header>

        <div class="w3-third w3-container w3-center"></div>

        <div class="w3-third w3-container w3-center" >
            <div class="w3-card-4">
                <div class="w3-container w3-center w3-theme-d3">
                    <h4>Login</h4>
                </div>
            </div>
            
            <div class="w3-card-4">
                <form action="login.php" method="POST" class="w3-margin w3-padding w3-center">
                    <div class="w3-margin-bottom">
                    <label>Username:</label>
                    <input type="text" class="w3-input w3-border <?= $username_err ? 'w3-border-red' : '' ?>" value="<?= $username ?>" name="username">
                    <?php if ($username_err): ?><span class="w3-text-red"><?= $username_err ?></span><?php endif; ?>
                    </div>

                    <div>
                    <label>Password:</label>
                    <input type="password" class="w3-input w3-border <?= $password_err ? 'w3-border-red' : '' ?>" value="<?= $password ?>" name="password">
                    <?php if ($password_err): ?><span class="w3-text-red"><?= $password_err ?></span><?php endif; ?>
                    </div>

                    <input type="submit" name="submit" value="Login" class="w3-button w3-margin-top w3-black">
                </form>
            </div>
        </div>
		
        <div class="w3-third w3-container w3-center"></div>

		<footer class="w3-container w3-center">
			<p id="signature">Made by Ciera Baucham</p>
		</footer>
	</div>	
</body>
</html>