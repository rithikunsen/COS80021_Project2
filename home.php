<?php
session_start();

if (isset($_SESSION['customerID'])) {
    // User is logged in; provide user-specific content
    echo '<h2>Welcome to ShopOnline!</h2>';
    echo '<p>This is the content for logged-in users.</p>';
    //display customer id 
    echo '<p>Your customer ID is ' . $_SESSION['customerID'] . '.</p>';
} else {
    // User is not logged in; show a message or redirect to the login/registration page
    echo '<h2>Welcome to ShopOnline!</h2>';
    echo '<p>Please <a href="login.html">login</a> or <a href="register.html">register</a> to access this content.</p>';
}
