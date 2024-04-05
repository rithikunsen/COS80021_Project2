<?php
$xmlfile = '../../data/customer.xml';

$doc = new DomDocument();

if (!file_exists($xmlfile)) {
    // If the XML file does not exist, create a root node 'customers'
    $customers = $doc->createElement('customers');
    $doc->appendChild($customers);
} else {
    // Load the XML file
    $doc->preserveWhiteSpace = FALSE;
    $doc->load($xmlfile);
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isEmailExists($doc, $email)
{
    $xpath = new DOMXPath($doc);
    $query = "//customer[email='$email']";
    $existingCustomer = $xpath->query($query);

    return $existingCustomer->length > 0;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle new customer registration
    $firstName = $_POST['first_name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatedPassword = $_POST['confirm_password'];

    // Check if all inputs are given
    if (empty($firstName) || empty($surname) || empty($email) || empty($password) || empty($repeatedPassword)) {
        echo "All fields are required.";
    } elseif ($password !== $repeatedPassword) {
        echo "Passwords do not match.";
    } elseif (isEmailExists($doc, $email)) {
        echo "Email already exists. Please choose a different email address.";
    } elseif (!isValidEmail($email)) {
        echo "Invalid email address.";
    } else {
        // Generate a customer ID (you can use any method you prefer)
        $customerID = uniqid('customer_');

        // Create a new customer element
        $customer = $doc->createElement('customer');
        $customer->setAttribute('id', $customerID);
        $customer->appendChild($doc->createElement('customerID', $customerID));
        $customer->appendChild($doc->createElement('email', $email));
        $customer->appendChild($doc->createElement('password', $password));
        $customer->appendChild($doc->createElement('first_name', $firstName));
        $customer->appendChild($doc->createElement('surname', $surname));

        // Append the new customer to the XML document
        $customers = $doc->documentElement;
        $customers->appendChild($customer);

        // Save the updated XML document
        $doc->save($xmlfile);
        //save customer id to sesssion
        session_start();
        $_SESSION['customerID'] = $customerID;

        // Send a welcome email
        $to = $email;
        $subject = "Welcome to ShopOnline!";
        $message = "Dear $firstName, welcome to use ShopOnline! Your customer id is $customerID and the password is $password.";
        $headers = "From: registration@shoponline.com.au";
        mail($to, $subject, $message, $headers);
        echo 200;
    }
}
