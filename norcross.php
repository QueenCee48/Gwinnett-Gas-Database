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
    <title>GCG - Norcross</title>
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
					<h4>Gwinnett Fuel - Norcross</h4>
				</div>
			</div>
		</div>

        <div class="w3-margin w3-padding">
            <?php
                include "connectDatabase.php";

                $sql  = "SELECT Date, RegularPrice, MidGradePrice, PremiumPrice ";
                $sql .= "FROM dailystationprice ";
                $sql .= "WHERE StationID = 2 ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='4' class='tableHeader'>Daily Gas Prices
                                    <a href='addGasPrice.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>Date</th>";
                    echo "      <th>RegularPrice</th>";
                    echo "      <th>MidGradePrice</th>";
                    echo "      <th>PremiumPrice</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['Date'] . "</td>";
                        echo " <td>" . $row['RegularPrice'] . "</td>";
                        echo " <td>" . $row['MidGradePrice'] . "</td>";
                        echo " <td>" . $row['PremiumPrice'] . "</td>";
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

                $sql  = "SELECT Date, Reg_Start, Reg_End, Mid_Start, Mid_End, Prem_Start, Prem_End ";
                $sql .= "FROM tankvolumereport ";
                $sql .= "WHERE StationID = 2 ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='7' class='tableHeader'>Daily Tank Volume (gal)
                                    <a href='addTankVolume.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>Date</th>";
                    echo "      <th>Reg_Start</th>";
                    echo "      <th>Reg_End</th>";
                    echo "      <th>Mid_Start</th>";
                    echo "      <th>Mid_End</th>";
                    echo "      <th>Prem_Start</th>";
                    echo "      <th>Prem_End</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['Date'] . "</td>";
                        echo " <td>" . $row['Reg_Start'] . "</td>";
                        echo " <td>" . $row['Reg_End'] . "</td>";
                        echo " <td>" . $row['Mid_Start'] . "</td>";
                        echo " <td>" . $row['Mid_End'] . "</td>";
                        echo " <td>" . $row['Prem_Start'] . "</td>";
                        echo " <td>" . $row['Prem_End'] . "</td>";
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

                $sql  = "SELECT DeliveryID, Date, Reg, Mid, Prem ";
                $sql .= "FROM delivery ";
                $sql .= "WHERE StationID = 2 ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='5' class='tableHeader'>Deliveries (gal)
                                    <a href='addDelivery.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>DeliveryID</th>";
                    echo "      <th>Date</th>";
                    echo "      <th>Reg</th>";
                    echo "      <th>Mid</th>";
                    echo "      <th>Prem</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['DeliveryID'] . "</td>";
                        echo " <td>" . $row['Date'] . "</td>";
                        echo " <td>" . $row['Reg'] . "</td>";
                        echo " <td>" . $row['Mid'] . "</td>";
                        echo " <td>" . $row['Prem'] . "</td>";
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

                $sql  = "SELECT Date, Grade, GallonSold, PricePerGal, SupplierPrice, Revenue, Cost, Profit ";
                $sql .= "FROM gasshiftreport ";
                $sql .= "WHERE StationID = 2 ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='w3-table w3-table-all w3-pale-blue'>";
                    echo "  <tr class='w3-black'>";
                    echo "      <th colspan='8' class='tableHeader'>Daily Gas Shift
                                    <a href='addGasShift.php' class='w3-button w3-border w3-right'>Add</a></th>";
                    echo "  </tr>";

                    echo "  <tr class='w3-theme-d3'>";
                    echo "      <th>Date</th>";
                    echo "      <th>Grade</th>";
                    echo "      <th>GallonSold</th>";
                    echo "      <th>PricePerGal</th>";
                    echo "      <th>SupplierPrice</th>";
                    echo "      <th>Revenue</th>";
                    echo "      <th>Cost</th>";
                    echo "      <th>Profit</th>";
                    echo "  </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo " <td>" . $row['Date'] . "</td>";
                        echo " <td>" . $row['Grade'] . "</td>";
                        echo " <td>" . $row['GallonSold'] . "</td>";
                        echo " <td>" . $row['PricePerGal'] . "</td>";
                        echo " <td>" . $row['SupplierPrice'] . "</td>";
                        echo " <td>" . $row['Revenue'] . "</td>";
                        echo " <td>" . $row['Cost'] . "</td>";
                        echo " <td>" . $row['Profit'] . "</td>";
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
            <p>200 Main St, Norcross, GA 30071</p>
			<p id="signature">Made by Ciera Baucham</p>
		</footer>
	</div>	
</body>
</html>