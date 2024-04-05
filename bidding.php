 <?php
    session_start();
    if (!isset($_SESSION['customerID'])) {
        header('Location: login.html');
        exit;
    }
    ?>
 <!DOCTYPE html>
 <html>

 <head>
     <title>Bidding</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <style>
         h1 {
             text-align: center;
             padding: 10px;
             background-color: #3498db;
             color: #fff;
         }

         h2 {
             text-align: center;
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

         .card {
             box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
             transition: 0.3s;
             width: 80%;
             padding: 20px;
             margin: 10px auto;
             border: 2px solid #3498db;
             border-radius: 5px;
         }

         .card:hover {
             box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
         }

         .card h4 {
             color: #3498db;
         }
     </style>
     <script>
         // JavaScript code to periodically refresh the displayed items
         function refreshItems() {
             // Implement code to retrieve and display items via AJAX every 5 seconds
             $.ajax({
                 url: 'get_items.php',
                 method: 'GET',
                 success: function(data) {
                     $('#itemList').html(data);
                 }
             });
         }
         // Call refreshItems() every 5 seconds
         setInterval(refreshItems, 5000);
     </script>
 </head>

 <body>
     <div class="container">
         <h1>ShopOnline</h1>
         <div class="topnav">
             <a <?php if (basename($_SERVER['PHP_SELF']) == 'home.html') echo 'class="active"'; ?> href="home.html">Home</a>
             <a <?php if (basename($_SERVER['PHP_SELF']) == 'listing.php') echo 'class="active"'; ?> href="listing.php">Listing</a>
             <a <?php if (basename($_SERVER['PHP_SELF']) == 'bidding.php') echo 'class="active"'; ?> href="bidding.php">Bidding</a>
             <a <?php if (basename($_SERVER['PHP_SELF']) == 'maintenance.html') echo 'class="active"'; ?> href="maintenance.html">Maintenance</a>
             <a <?php if (basename($_SERVER['PHP_SELF']) == 'logout.php') echo 'class="active"'; ?> href="logout.php">Logout</a>
         </div>
         <hr>
         <p class="text-center">Current auction items are listed below. To place a bid for an items, use the Place Bid button. <br>
             <strong>NOTE:</strong> Items remaining time and bid prices are updated every 5 seconds.
         </p>
         <div id="itemList">
             <!-- Display items here -->
         </div>
     </div>
     <!-- Include Bootstrap JS -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 </body>