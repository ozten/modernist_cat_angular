<?php include 'header.php'; ?>

<?
error_reporting(E_ALL ^E_DEPRECATED);

require_once('./config.php');
require_once('./cart.php');

// Validate product id
// TODO check no '/' exist...
$product_id = $_GET['product_id'];

// Load Product databse
$rawJson = file_get_contents('../products/' . $product_id . '.json');
if ($rawJson == FALSE) {
  die('No such product');
}

// Our Databases are from our .json files
$productDb = json_decode($rawJson);
$optionsDb = json_decode(file_get_contents('../products/options.json'));

// Things that can change the price
// On the feeder - size affects price... Default to Double if no 'asize' present
// addons - addonsSelected that come in as true
// $product_id.json->addons->$addon_name->price

$choiceValues = json_decode($_GET['choiceValues']);

// Default size for feeder... irrelevant for other products
$feederSize = 'Double';

// Feeders size affect price
if ($product_id == 'feeder') {
    if ($choiceValues && $choiceValues->asize) {
        if (in_array($choiceValues->asize, $optionsDb->size->choices)) {
	    $feederSize = $choiceValues->asize;
        } else {
   	    die('Invalid size, invalid option');
        }
    }
}

// Decode this one as an array instead of an Object
// to make looping over keys easier

// addonsSelected:{"elounge":true}
$addonsSelected = json_decode($_GET['addonsSelected'], TRUE);

//  addonValues:{"elounge":"Chipper Stone"} 
$addonValues = json_decode($_GET['addonValues'], TRUE);

foreach($addonsSelected as $addon => $value) {
    if ($value == 'true') {
        $addonValue = $addonValues[$addon];
        if ($productDb->addons->{$addon}) {
	    $addonKey = $productDb->addons->{$addon}->options;
	    if (in_array($addonValue, $optionsDb->{$addonKey}->choices) == FALSE) {
	        die('Invalid Addon choice, $addon cannot be $value for $product_id product');
            }
        } else {
            die('Invalid addon $addon is not valid for $product_id product');
        }
    }
}

// Validate Options and Addons which don't affect the price
$options = json_decode($_GET['options'], TRUE);

// All options must be valid for this product
foreach($options as $option => $value) {
    // Is this product allowed to have this option?

    if (in_array($option, array_keys(get_object_vars($productDb->options)))) {
        // Is the value of this option a legal value?
        $optionKey = $productDb->options->{$option}->options;
        if (in_array($value, $optionsDb->{$optionKey}->choices) == FALSE) {
	    die("$option cannot be set to $value for the $product_id product");
        }
    } else {
        die("$option option isn't valid for the $product_id product");
    }
}

$total_price = calcualte_total_price($product_id, $productDb, $optionsDb, $addonsSelected, $feederSize);
$stripe_description = $productDb->name . ' ' . $productDb->subtitle . ' total: $' . $total_price;

?>

<form action="checkout.php" method="POST" id="payment-form">
  <!-- Crystal can get Customer's Name out of Stripe payment, or we can ask for it twice. -->
  <input name="product_id" value="<?= $product_id ?>" type="hidden" />
  <input name="description" value="<?= $stripe_description ?>" type="hidden" />
  <input name="feeder_size" value="$feederSize" type="hidden" />

  <? foreach($options as $option => $value) { ?>
    <input name="option_<?= $option ?>" value="<?= $value ?>" type="hidden" />
  <? } ?>

  <? foreach($addonsSelected as $addon => $value) { 
        $addonValue = $addonValues[$addon]; ?>
    <input name="addon_<?= $addon ?>" value="<?= $addonValue ?>" type="hidden" />
  <? } ?>

<div class="six columns">
</div> 
<div class="six columns"> 
  <fieldset>
    <legend>Contact Info</legend>
  <ul class="form">
      <li>
      <label for="email">Your Name</label>
      <input id="email" name="name" value="Joe Smith" />
      </li>
      <li>
      <label for="email">Email</label>
      <input id="email" name="email" value="foo@bar.com" />
      </li>
   </ul>
   </fieldset>   
   <fieldset>
    <legend>Shipping Address</legend> 
    <ul class="form">
      <li> 
      <label for="address_1">Address Line 1</label>
      <input id="address_1" name="address_1" value="1234 memory lane"/>
      </li>
      <li>
      <label for="address_2">Address Line 2</label>
      <input id="address_2" name="address_2" value=""  />
      </li>
      <li class="six columns">
      <label for="city">City</label>
      <input id="city" name="city" value="Seattle" />
      </li>
      <li class="two columns">
      <label for="state">State</label>
      <input id="state" name="state" value="WA"  />
      </li>
      <li class="four columns last">
      <label for="zip">Zip</label>
      <input id="zip" name="zip" value="98177" />
      </li>
    </ul>
  </fieldset>
  <fieldset>
    <legend>Comments</legend>
      <label for="comments">Supply any comments or special requests</label>
      <textarea id="comments" name="comments" value="comments" cols="10" rows="3" ></textarea>
  </fieldset>

  <script
    src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
    data-key="<?= $STRIPE_PUBLISHABLE_KEY ?>"
    data-amount="<?= $total_price * 100 ?>"
    data-name="Modernistcat"
    data-description="<?= $stripe_description ?>"
    data-image="/app/<?= $productDb->images[0] ?>">
  </script>
</form>
</div>
<?php include 'footer.php'; ?>