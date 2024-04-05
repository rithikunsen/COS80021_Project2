<?php

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'process_items') {
        // Handle "Process Auction Items" action

        // Load the XML file
        $xml = new DOMDocument();
        $xml->load('../../data/auction.xml');

        // Get the current date and time
        $currentTimestamp = time();

        // Get all items with "in_progress" status
        $items = $xml->getElementsByTagName('item');
        foreach ($items as $item) {
            $status = $item->getElementsByTagName('status')->item(0)->nodeValue;

            // Check only items with "in_progress" status
            if ($status == "in_progress") {
                $startDateTime = $item->getElementsByTagName('start_date')->item(0)->nodeValue . ' ' . $item->getElementsByTagName('start_time')->item(0)->nodeValue;
                $startTimeStamp = strtotime($startDateTime);
                $duration = $item->getElementsByTagName('duration')->item(0)->nodeValue;
                $endTimeStamp = $startTimeStamp + ($duration * 60);

                if ($currentTimestamp >= $endTimeStamp) {
                    // Calculate if the item is expired

                    // Get current bid price and reserve price
                    $currentBidPrice = $item->getElementsByTagName('current_bid_price')->item(0)->nodeValue;
                    $reservePrice = $item->getElementsByTagName('reserve_price')->item(0)->nodeValue;

                    // Determine the item's new status ("sold" or "failed")
                    $newStatus = ($currentBidPrice >= $reservePrice) ? "sold" : "failed";

                    // Update the item's status in the XML document
                    $item->getElementsByTagName('status')->item(0)->nodeValue = $newStatus;
                }
            }
        }

        // Save the updated XML file
        $xml->save('../../data/auction.xml');

        // Return a message to indicate the process is complete
        echo "Auction items processing is complete.";
    } elseif ($_POST['action'] === 'generate_report') {
        // Handle "Generate Report" action

        // Load the XML file
        $xml = new DOMDocument();
        $xml->load('../../data/auction.xml');

        // Create a table to display sold and failed items
        $report = "<table class='modern-table'>";
        $report .= "<tr><th>Item Number</th><th>Status</th><th>Current Bid Price</th><th>Reserve Price</th></tr>";

        // Initialize revenue variable
        $revenue = 0;

        // Get all items and calculate revenue
        $items = $xml->getElementsByTagName('item');
        foreach ($items as $item) {
            $status = $item->getElementsByTagName('status')->item(0)->nodeValue;

            if ($status == "sold" || $status == "failed") {
                $itemNumber = $item->getAttribute('item_number');
                $currentBidPrice = $item->getElementsByTagName('current_bid_price')->item(0)->nodeValue;
                $reservePrice = $item->getElementsByTagName('reserve_price')->item(0)->nodeValue;

                // Calculate revenue based on the status
                if ($status == "sold") {
                    $revenue += ($currentBidPrice * 0.03); // 3% of the sold price
                } else {
                    $revenue += ($reservePrice * 0.01); // 1% of the reserved price
                }

                // Add item information to the report
                $report .= "<tr><td>$itemNumber</td><td>$status</td><td>$currentBidPrice</td><td>$reservePrice</td></tr>";
            }
        }

        $report .= "</table>";

        // Add total revenue to the report
        $report .= "<p class='total-revenue'>Total Revenue: $revenue</p>";

        // Remove sold and failed items from the XML file
        foreach ($items as $item) {
            $status = $item->getElementsByTagName('status')->item(0)->nodeValue;
            if ($status == "sold" || $status == "failed") {
                $item->parentNode->removeChild($item);
            }
        }

        // Save the updated XML file
        $xml->save('../../data/auction.xml');

        // Return the generated report
        echo $report;
    }
}
