<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .countdown {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        // Load XML file
        $xml = new DOMDocument();
        $xml->load('../../data/auction.xml');

        // Get all items
        $items = $xml->getElementsByTagName('item');
        //check if there is any items
        if ($items->length == 0) {
            echo "<h2>No items found.</h2>";
            exit();
        } else {
            echo "<h2 class='text-center mt-4 mb-4'>Items for Auction</h2>";
            // Display items
            foreach ($items as $item) {
                $itemNumber = $item->getAttribute('item_number');
                $itemName = $item->getElementsByTagName('item_name')->item(0)->nodeValue;
                $category = $item->getElementsByTagName('category')->item(0)->nodeValue;
                $description = substr($item->getElementsByTagName('description')->item(0)->nodeValue, 0, 30); // Truncate description to the first 30 characters
                $buyItNowPrice = $item->getElementsByTagName('buy_it_now')->item(0)->nodeValue;
                $currentBidPrice = $item->getElementsByTagName('current_bid_price')->item(0)->nodeValue;

                // Calculate days, hours, minutes, and seconds remaining for bidding and don't refresh to the original date and time when the page is refreshed use the updated time remaining
                $startDateTime = $item->getElementsByTagName('start_date')->item(0)->nodeValue . ' ' . $item->getElementsByTagName('start_time')->item(0)->nodeValue;
                $startTimeStamp = strtotime($startDateTime);
                $endTimeStamp = $startTimeStamp + 60 * 60 * 24 * 7; // 7 days
                $timeRemaining = $endTimeStamp - time();
                $daysRemaining = floor($timeRemaining / (60 * 60 * 24));
                $hoursRemaining = floor(($timeRemaining % (60 * 60 * 24)) / (60 * 60));
                $minutesRemaining = floor(($timeRemaining % (60 * 60)) / 60);
                $secondsRemaining = $timeRemaining % 60;

                // Display the auction item using Bootstrap styling
                echo "<div class='card mb-4'>";
                echo "<div class='card-body'>";
                echo "<h4 class='card-title'>$itemName</h4>";
                echo "<p class='card-text'><strong>Item Number:</strong> $itemNumber</p>";
                echo "<p class='card-text'><strong>Category:</strong> $category</p>";
                echo "<p class='card-text'><strong>Description:</strong> $description</p>";
                echo "<p class='card-text'><strong>Buy-it-Now Price:</strong> $buyItNowPrice</p>";
                echo "<p class='card-text'><strong>Current Bid Price:</strong> $currentBidPrice</p>";
                echo "<p class='card-text'><strong>Time Remaining:</strong> $daysRemaining days $hoursRemaining hours $minutesRemaining minutes $secondsRemaining seconds</p>";
                echo "<form action='process_bid.php' method='post' class='mt-3'>";
                echo "<input type='hidden' name='item_number' value='$itemNumber' />";
                echo '<div class="input-group mb-3">';
                echo '  <input type="number" class="form-control" placeholder="Enter a value to Place Bid..." name="bid_price" aria-label="Enter a value to Place Bid..." aria-describedby="basic-addon2">';
                echo '  <div class="input-group-append">';
                echo '    <button class="btn btn-outline-secondary" type="submit" name="submit_bid">Place Bid</button>';
                echo '  </div>';
                echo '</div>';
                echo "</form>";
                echo "<form action='process_buy_now.php' method='post' class='mt-2'>";
                echo "<input type='hidden' name='item_number' value='$itemNumber' />";
                echo "<button type='submit' name='buy_now' class='btn btn-success'>Buy It Now</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
    <!-- Include Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>