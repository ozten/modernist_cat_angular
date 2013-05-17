<?
require_once('./stripe-php-1.7.15/lib/Stripe.php');
require_once('./config.php');

/* TODO read product database from Angluar JSON */
$products = array(
  'console' => array(
      'long_desc' => 'Long Circa50: Standard - Scratch:Tangerine, Additional Carpet:none',
      'price' => '59900'
  ),
  'b' => array(
      'long_desc' => 'Circa50: Standard',
      'price' => '44900'
  ),
  'c' => array(
      'long_desc' => 'Circa50: Standard',
      'price' => '7900'
  )
);

// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://manage.stripe.com/account
Stripe::setApiKey($STRIPE_SECRET_KEY);

// What are they buying?
$product = $_POST['product_id'];

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];
$email = $_POST['email'];
$address_1 = $_POST['address_1'];
$address_2 = $_POST['address_2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];

// Create the charge on Stripe's servers - this will charge the user's card
try {
$charge = Stripe_Charge::create(array(
  "amount" => $products[$_POST['product_id']]['price'], // amount in cents, again
  "currency" => "usd",
  "card" => $token,
  "description" => $email . " --- " . $address_1 . " " . $address_2 . " " . $city . " " . $state . " " . $zip . " --- 1 product --- " . $products[$_POST['product_id']]['long_desc'])
);
} catch(Stripe_CardError $e) {
  // The card has been declined
}

header('Location: http://ozten.com/random/modernistcat/');
die();

?>