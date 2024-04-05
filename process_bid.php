<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            border: 2px solid #3498db;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
        }

        .topnav {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px 0;
        }

        .topnav a {
            padding: 10px 20px;
            margin: 0 10px;
            border: 2px solid #3498db;
            color: #3498db;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .topnav a.active,
        .topnav a:hover {
            background-color: #3498db;
            color: #fff;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #3498db;
            background-color: #f2f2f2;
            border-radius: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
        }

        input[type="reset"] {
            width: 100%;
            background-color: #ccc;
            color: #333;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error-message {
            color: #ff0000;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>ShopOnline</h1>
        <div class="topnav">
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'home.php') echo 'class="active"'; ?> href="home.php">Home</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'listing.php') echo 'class="active"'; ?> href="listing.php">Listing</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'bidding.php') echo 'class="active"'; ?> href="bidding.php">Bidding</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'maintenance.php') echo 'class="active"'; ?> href="maintenance.php">Maintenance</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'logout.php') echo 'class="active"'; ?> href="logout.php">Logout</a>
        </div>
        <hr>
    </div>
</body>

</html>
<?php
// Check if the form was submitted
if (isset($_POST['submit_bid'])) {
    // Get item_number, bid_price, and any other required data from the form
    $itemNumber = $_POST['item_number'];
    $bidPrice = $_POST['bid_price'];
    // You should also get the customer's ID here (e.g., from a session or user authentication)

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load('../../data/auction.xml');

    // Find the item with the given item_number
    $items = $xml->getElementsByTagName('item');
    $itemToUpdate = null;
    foreach ($items as $item) {
        if ($item->getAttribute('item_number') == $itemNumber) {
            $itemToUpdate = $item;
            break;
        }
    }

    if ($itemToUpdate) {
        // Get the current bid price for the item
        $currentBidPrice = $itemToUpdate->getElementsByTagName('current_bid_price')->item(0)->nodeValue;

        // Check if the new bid is acceptable
        if ($bidPrice > $currentBidPrice) {
            // Update the item in the XML document with the new bid price and the bidder's customer ID
            $itemToUpdate->getElementsByTagName('current_bid_price')->item(0)->nodeValue = $bidPrice;
            // You should also update the bidder's customer ID here
            // Save the updated XML file
            $xml->save('../../data/auction.xml');

            // Apply CSS styles for modern look and centering
            echo '<div style="text-align: center; font-size: 24px; padding: 20px;">Thank you! Your bid is recorded in ShopOnline.</div>';
        } else {
            // Send a message if the bid is not valid
            echo '<div style="text-align: center; font-size: 24px; padding: 20px;">Sorry, your bid is not valid.</div>';
        }
    } else {
        // Handle the case where the item was not found
        echo '<div style="text-align: center; font-size: 24px; padding: 20px;">Item not found.</div>';
    }
} else {
    // Handle the case where the form was not submitted
    echo '<div style="text-align: center; font-size: 24px; padding: 20px;">Form not submitted.</div>';
}
?>