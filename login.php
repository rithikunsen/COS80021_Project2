<?php
// Start a session
session_start();

$xmlfile = '../../data/customer.xml';

$doc = new DomDocument();

if (!file_exists($xmlfile)) {
    // If the XML file does not exist, create a root node 'customers'
    $customers = $doc->createElement('customers');
    $doc->appendChild($customers);
} else {
    $doc->preserveWhiteSpace = FALSE;
    $doc->load($xmlfile);
}

// Function to check if an email is valid
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check if an email is unique in the XML document
function isEmailUnique($doc, $email)
{
    $xpath = new DOMXPath($doc);
    $customerQuery = "//customer[email='$email']";
    $existingCustomer = $xpath->query($customerQuery);
    return $existingCustomer->length === 0;
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Both email and password are required.";
    } else if (!isValidEmail($email)) {
        echo "Invalid email address.";
    } else {
        if (isEmailUnique($doc, $email)) {
            echo "Email address not found in the system.";
        } else {
            $xpath = new DOMXPath($doc);
            $customerQuery = "//customer[email='$email'][password='$password']";
            $existingCustomers = $xpath->query($customerQuery);

            if ($existingCustomers->length > 0) {
                $customer = $existingCustomers->item(0);
                if ($customer instanceof DOMElement) {
                    $customerID = $customer->getAttribute('id');
                    $_SESSION['customerID'] = $customerID;
                    echo 200;
                } else {
                    echo "Error: Customer element not found.";
                }
            } else {
                echo "Invalid email or password.";
            }
        }
    }
}
