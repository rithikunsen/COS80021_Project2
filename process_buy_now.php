<?php
// Check if the "Buy It Now" button was clicked
if (isset($_POST['buy_now'])) {
    // Get item_number and customer_id from the form
    $itemNumber = $_POST['item_number'];
    // You should also get the customer's ID here (e.g., from a session or user authentication)

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load('../../data/auction.xml');

    // Find the item with the given item_number
    $items = $xml->getElementsByTagName('item');
    $itemToBuyNow = null;
    foreach ($items as $item) {
        if ($item->getAttribute('item_number') == $itemNumber) {
            $itemToBuyNow = $item;
            break;
        }
    }

    if ($itemToBuyNow) {
        // Get the buy-it-now price for the item
        $buyItNowPrice = $itemToBuyNow->getElementsByTagName('buy_it_now')->item(0)->nodeValue;

        // Update the item in the XML document
        $itemToBuyNow->getElementsByTagName('current_bid_price')->item(0)->nodeValue = $buyItNowPrice;
        // You should also set the bidder's customer ID and update the status to "sold" here

        // Save the updated XML file
        $xml->save('../../data/auction.xml');

        // Send an acknowledgement to the customer
        echo "Thank you for purchasing this item.";
    } else {
        // Handle the case where the item was not found
        echo "Item not found.";
    }
} else {
    // Handle the case where the "Buy It Now" button was not clicked
    echo "Buy It Now button not clicked.";
}
