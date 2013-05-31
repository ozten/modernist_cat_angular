<?
error_reporting(E_ALL ^E_DEPRECATED);

require_once('./stripe-php-1.7.15/lib/Stripe.php');
require_once('./config.php');


// Validate product id
// TODO check no '/' exist...
$product_id = $_POST['product_id'];
$options = json_decode($_POST['options']);
$incomingAddonsSelected = $_POST['addonsSelected'];
$incomingAddonValues = $_POST['addonValues'];

require_once('./cart.php');
require_once('./email_sale.php');

// What are they buying?
$product_id = $_POST['product_id'];
// Load Product databse
$rawJson = file_get_contents('../products/' . $product_id . '.json');
if ($rawJson == FALSE) {
  die('No such product');
}
// Our Databases are from our .json files
$productDb = json_decode($rawJson);
$optionsDb = json_decode(file_get_contents('../products/options.json'));
$addonsSelected = array();

if ($product_id == 'feeder') {
    array_push($optionDetails, 'feeder_size', $_POST['feeder_size']);
}

//$total_price = calculate_total_price($product_id, $productDb, $optionsDb, $addonsSelected, $feederSize);

// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://manage.stripe.com/account
Stripe::setApiKey($STRIPE_SECRET_KEY);

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];
$name = $_POST['name'];
$email = $_POST['email'];
$address_1 = $_POST['address_1'];
$address_2 = $_POST['address_2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$comment = $_POST['comments'];

$emailAddonsSelected = "";
foreach ($addonSelections as $addonSelected) {
  if (count($addonSelected) >= 3) {
    $emailAddonsSelected .=  $addonSelected[2] . ': ' .$addonSelected[1] . "\n"; 
  }
}

$emailOption = "";
foreach((array) $options as $key => $value) {
  $emailOption .= $key . ": " . $value . "\n";
}

$stripe_description = $name . "<" . $email . "> --- \n\n" . $address_1 . " \n" .
    $address_2 . " \n" . $city . " " . $state . " " . $zip .
    "\n\n --- 1 product --- \n" . $stripe_description .
    "\n\n --- Options: \n" . $emailOption .
    "\n\n --- Addons: \n" . $emailAddonsSelected .
    "\n\n --- Comment: \n" . $comment;

// Create the charge on Stripe's servers - this will charge the user's card
try {

  $charge = Stripe_Charge::create(array(
    "amount" => $total_price * 100,
    "currency" => "usd",
    "card" => $token,
    "description" => $stripe_description
  ));

  // Email Crystal new sale
  $body = $stripe_description;
  $body .= "\n\nPrice: $" . $total_price . " on Stripe at https://manage.stripe.com/test/payments/" . $_POST['stripeToken'];

  if (emailSale($CRYSTAL_EMAIL, "$name <$email>", $body) == FALSE) {
    // Yikes, didn't send email... TODO
  }
  header('Location: http://dev.modernistcat.com/#/thanks');
  die();

} catch(Stripe_CardError $e) {
  // The card has been declined
  echo "Sorry, we're having trouble processing this card.";
}
?>