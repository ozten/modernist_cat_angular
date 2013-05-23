<?php include 'header.php'; ?>

<?
require_once('./config.php');

// Validate product id
// TODO check no '/' exist...
$product_id = $_GET['product_id'];

$total_price = 0;

// Load Product databse
$rawJson = file_get_contents('../app/products/' . $product_id . '.json');
if ($rawJson == FALSE) {
  die('No such product');
}

// Our Databases are from our .json files
$productDb = json_decode($rawJson);
$optionsDb = json_decode(file_get_contents('../app/products/options.json'));

if ($productDb->price) {
    $total_price += $productDb->price;
} else {
    die('Produce database error, no price');
}

// Things that can change the price
// On the feeder - size affects price... Default to Double if no 'asize' present
// addons - addonsSelected that come in as true
// $product_id.json->addons->$addon_name->price

$choiceValues = json_decode($_GET['choiceValues']);

// Feeders size affect price
if ($product_id == 'feeder') {
    $size = 'Double';
    if ($choiceValues && $choiceValues->asize) {
        if (in_array($choiceValues->asize, $optionsDb->size->choices)) {
	    $size = $choiceValues->asize;
	    echo "Setting to size $size";
        } else {
   	    die('Invalid size, invalid option');
        }
    }
    // price code will be 0, 1, or 2
    $priceCode = array_search($size, $optionsDb->size->choices);
    $total_price += $optionsDb->size->price[$priceCode];
}

//addonsSelected={}&
// Decode this one as an array instead of an Object
// to make looping over keys easier
$addonsSelected = json_decode($_GET['addonsSelected'], TRUE);

foreach($addonsSelected as $addon => $value) {
    if ($value == 'true') {
        if ($productDb->addons->{$addon}) {
            $total_price += $productDb->addons->{$addon}->price;
        } else {
            die('Invalid addon');
        }
    }
}

// Validate Options and Addons which don't affect the price
$options = json_decode($_GET['options']);
//options={"scratch": "Bark"}
if ($options->scratch) {
    if (in_array($options->scratch, $optionsDb->scratch->choices) == FALSE) {
        die('Invalid scratch choice');
    }
}

//addonValues={}
$addonValues = json_decode($_GET['addonValues']);

$stripe_description = $productDb->name . ' ' . $productDb->subtitle . ' total: $' . $total_price;

?>

<form action="checkout.php" method="POST" id="payment-form">
  <!-- Crystal can get Customer's Name out of Stripe payment, or we can ask for it twice. -->
  <input name="product_id" value="<?= $product_id ?>" type="hidden" />
  <? if ($options->scratch) { ?>
    <input name="scratch"    value="<?= $options->scratch ?>" type="hidden" />
  <? } ?>
  <input name="door"       value="<?= $door ?>" type="hidden" />
  <input name="ent_side"   value="<?= $ent_side ?>" type="hidden" />
  <input name="laminate"   value="<?= $laminate ?>" type="hidden" />
  <? if ($addonsSelected->scratch2) { ?>
    <input name="additional_scratch" value="<?= $addonValues->scratch2 ?>" type="hidden" />
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
    data-amount="<?= $total_price ?>"
    data-name="Modernistcat"
    data-description="<?= $stripe_description ?>"
    data-image="/app/<?= $productDb->images[0] ?>">
  </script>
</form>
</div>
<?php include 'footer.php'; ?>