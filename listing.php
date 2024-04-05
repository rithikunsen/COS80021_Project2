<?php
session_start();
if (!isset($_SESSION['customerID'])) {
    header('Location: login.html');
    exit;
} else {
    $xmlfile = '../../data/auction.xml';

    $doc = new DomDocument();

    if (!file_exists($xmlfile)) { // If the XML file does not exist, create a root node 'items'
        $items = $doc->createElement('items');
        $doc->appendChild($items);
    } else { // Load the XML file
        $doc->preserveWhiteSpace = FALSE;
        $doc->load($xmlfile);
    }

    $errorMessage = ''; // Initialize an error message variable
    $successMessage = ''; // Initialize a success message variable

    if (isset($_POST['list_item'])) {
        $item_name = $_POST['item_name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $start_price = $_POST['start_price'];
        $reserve_price = $_POST['reserve_price'];
        $buy_it_now = $_POST['buy_it_now'];
        $duration_days = $_POST['duration_days'];
        $duration_hours = $_POST['duration_hours'];
        $duration_minutes = $_POST['duration_minutes'];

        // Validate inputs
        if ($start_price > $reserve_price) {
            $errorMessage = "Start price must not be more than the reserve price.";
        } elseif ($reserve_price >= $buy_it_now) {
            $errorMessage = "Reserve price must be less than the Buy-it-Now price.";
        } else {
            // Generate item number and other system-generated information
            $item_number = uniqid('item_');
            $seller_id = $_SESSION['customerID']; // Assuming the seller is logged in

            $start_date = date('Y-m-d');
            $start_time = date('H:i:s');
            $status = 'in_progress';

            // Create a new item element
            $newItem = $doc->createElement('item');
            $newItem->setAttribute('item_number', $item_number);
            $newItem->appendChild($doc->createElement('seller_id', $seller_id));
            $newItem->appendChild($doc->createElement('start_date', $start_date));
            $newItem->appendChild($doc->createElement('start_time', $start_time));
            $newItem->appendChild($doc->createElement('status', $status));
            $newItem->appendChild($doc->createElement('current_bidder_id', '')); // Initial value
            $newItem->appendChild($doc->createElement('item_name', $item_name));
            $newItem->appendChild($doc->createElement('category', $category));
            $newItem->appendChild($doc->createElement('description', $description));
            $newItem->appendChild($doc->createElement('start_price', $start_price));
            $newItem->appendChild($doc->createElement('reserve_price', $reserve_price));
            $newItem->appendChild($doc->createElement('buy_it_now', $buy_it_now));
            $newItem->appendChild($doc->createElement('current_bid_price', $start_price)); // Initial value
            // Show duration in days, hours, and minutes
            $newItem->appendChild($doc->createElement('duration', $duration_days . ' days ' . $duration_hours . ' hours ' . $duration_minutes . ' minutes'));

            // Append the new item to the XML document
            $items = $doc->getElementsByTagName('items')->item(0);
            if ($items) {
                $items->appendChild($newItem);
                // Save the updated XML document
                $doc->formatOutput = true;
                $doc->save($xmlfile);
                $successMessage = "Item listed successfully.";
            } else {
                $errorMessage = "Error: The XML structure is invalid.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>List Item</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h1 {
            text-align: center;
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

        .listing-form {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #3498db;
            background-color: #f2f2f2;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>ShopOnline</h1>
        <!-- Your navigation bar here -->
        <div class="topnav">
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'home.php') echo 'class="active"'; ?> href="home.html">Home</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'listing.php') echo 'class="active"'; ?> href="listing.php">Listing</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'bidding.php') echo 'class="active"'; ?> href="bidding.php">Bidding</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'maintenance.php') echo 'class="active"'; ?> href="maintenance.html">Maintenance</a>
            <a <?php if (basename($_SERVER['PHP_SELF']) == 'logout.php') echo 'class="active"'; ?> href="logout.php">Logout</a>
        </div>
        <hr>
        <h2 class="text-center mt-4 mb-4">List Item for Auction</h2>
        <div class="listing-form">
            <form action="listing.php" method="post" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="item_name">Item Name:</label>
                    <input type="text" class="form-control" name="item_name" id="item_name" required>
                    <div class="invalid-feedback">
                        Please provide an item name.
                    </div>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" name="category" required>
                        <!-- <option value="Other">Other</option>
                        <option value="Phone">Phone</option>
                        <option value="Car">Car</option>
                        <option value="Clothes">Clothes</option>
                        <option value="Shoes">Shoes</option>
                        <option value="Watches">Watches</option> -->
                        <?php
                        // Load categories from the auction.xml file
                        $xmlfile = '../../data/auction.xml';
                        $doc = new DomDocument();
                        $doc->preserveWhiteSpace = FALSE;
                        $doc->load($xmlfile);
                        $categories = $doc->getElementsByTagName('category');
                        foreach ($categories as $category) {
                            echo "<option value='" . $category->nodeValue . "'>" . $category->nodeValue . "</option>";
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select a category.
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description" id="description" required></textarea>
                    <div class="invalid-feedback">
                        Please provide a description.
                    </div>
                </div>

                <div class="form-group">
                    <label for="start_price">Start Price:</label>
                    <input type="number" class="form-control" name="start_price" id="start_price" min="0" required oninput="validateNumberInput(this)">
                    <div class="invalid-feedback">
                        Please provide a valid start price.
                    </div>
                </div>

                <div class="form-group">
                    <label for="reserve_price">Reserve Price:</label>
                    <input type="number" class="form-control" name="reserve_price" id="reserve_price" min="0" required oninput="validateNumberInput(this)">
                    <div class="invalid-feedback">
                        Please provide a valid reserve price.
                    </div>
                </div>

                <div class="form-group">
                    <label for="buy_it_now">Buy-it-Now Price:</label>
                    <input type="number" class="form-control" name="buy_it_now" id="buy_it_now" min="0" required oninput="validateNumberInput(this)">
                    <div class="invalid-feedback">
                        Please provide a valid Buy-it-Now price.
                    </div>
                </div>

                <label>Duration:</label>
                <div class="form-row">
                    <div class="form-group col">
                        <input type="number" class="form-control" name="duration_days" id="duration_days" min="0" placeholder="Days" required oninput="validateNumberInput(this)">
                        <div class="invalid-feedback">
                            Please provide a valid number of days.
                        </div>
                    </div>
                    <div class="form-group col">
                        <input type="number" class="form-control" name="duration_hours" id="duration_hours" min="0" placeholder="Hours" required oninput="validateNumberInput(this)">
                        <div class="invalid-feedback">
                            Please provide a valid number of hours.
                        </div>
                    </div>
                    <div class="form-group col">
                        <input type="number" class="form-control" name="duration_minutes" id="duration_minutes" min="0" placeholder="Minutes" required oninput="validateNumberInput(this)">
                        <div class="invalid-feedback">
                            Please provide a valid number of minutes.
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" name="list_item" value="List Item" class="btn btn-primary">
                    <input type="reset" name="reset" value="Reset" class="btn btn-secondary">
                </div>
            </form>
        </div>
        <!-- Display error message if there is one using javascript alert -->
        <?php
        if ($errorMessage) {
            echo "<script>alert('$errorMessage');</script>";
        }
        ?>
        <!-- Display success message if there is one using javascript alert -->
        <?php
        if ($successMessage) {
            //go to bidding poage
            echo "<script>alert('$successMessage');window.location.href='bidding.php';</script>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script>
        // Bootstrap form validation
        function validateNumberInput(inputField) {
            // Use a regular expression to filter out non-numeric characters
            inputField.value = inputField.value.replace(/[^0-9]/g, '');
        }
        (function() {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })

            //reset
            document.querySelector('input[type="reset"]').addEventListener('click', function() {
                document.querySelector('.needs-validation').classList.remove('was-validated');
            });
        })()

        //reset
    </script>
</body>

</html>