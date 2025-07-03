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
    <title>GCG - Home</title>
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
					<h4>Overview</h4>
				</div>
			</div>
		</div>

		<div class="w3-margin w3-padding">
            <?php
                include "connectDatabase.php";

                $sql  = "SELECT * ";
                $sql .= "FROM dailysupplierprice ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='4' class='tableHeader'>Daily Supplier Prices
                                    <a href='addSupplierPrice.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>Date</th>";
                    echo "      <th>Regular</th>";
                    echo "      <th>MidGrade</th>";
                    echo "      <th>Premium</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['Date'] . "</td>";
                        echo " <td>" . $row['Regular'] . "</td>";
                        echo " <td>" . $row['MidGrade'] . "</td>";
                        echo " <td>" . $row['Premium'] . "</td>";
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

                $sql  = "SELECT * ";
                $sql .= "FROM station ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='7' class='tableHeader'>Stations
                                    <a href='addStation.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>StationID</th>";
                    echo "      <th>StationName</th>";
                    echo "      <th>Address</th>";
                    echo "      <th>City</th>";
					echo "      <th>ZipCode</th>";
					echo "      <th>FreightCharge</th>";
					echo "      <th>EnvironmentalFee</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['StationID'] . "</td>";
                        echo " <td>" . $row['StationName'] . "</td>";
                        echo " <td>" . $row['Address'] . "</td>";
                        echo " <td>" . $row['City'] . "</td>";
						echo " <td>" . $row['ZipCode'] . "</td>";
						echo " <td>" . $row['FreightCharge'] . "</td>";
						echo " <td>" . $row['EnvironmentalFee'] . "</td>";
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

                $sql  = "SELECT *, (TotalRevenue - TotalCost) AS TotalProfit ";
                $sql .= "FROM dailysummary ";
                $sql .= "ORDER BY Date ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='6' class='tableHeader'>Daily Summary
                                    <a href='addSummary.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>Date</th>";
					echo "      <th>StationID</th>";
                    echo "      <th>TotalGallonsSold</th>";
                    echo "      <th>TotalRevenue</th>";
                    echo "      <th>TotalCost</th>";
                    echo "      <th>TotalProfit</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['Date'] . "</td>";
						echo " <td>" . $row['StationID'] . "</td>";
                        echo " <td>" . $row['TotalGallonsSold'] . "</td>";
                        echo " <td>" . $row['TotalRevenue'] . "</td>";
                        echo " <td>" . $row['TotalCost'] . "</td>";
                        echo " <td>" . $row['TotalProfit'] . "</td>";
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