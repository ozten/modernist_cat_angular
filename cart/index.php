<?

require_once('./config.php');

// Load Product databse
// Validate inputs
$product_id = $_GET['product_id'];
$scratch = $_GET['scratch'];
$door = $_GET['door'];
$ent_side = $_GET['ent_side'];
$laminate = $_GET['laminate'];
$additional_scratch = $_GET['additional_scratch']; // Boolean

// From Database after validation or Config (Security sensative)
$price = 59900;
$description = "Long Circa50: Standard ($599.00)";

?>

<form action="checkout.php" method="POST" id="payment-form">
  <!-- Crystal can get Customer's Name out of Stripe payment, or we can ask for it twice. -->
  <input name="product_id" value="<?= $product_id ?>" type="hidden" />
  <input name="scratch"    value="<?= $scratch ?>" type="hidden" />
  <input name="door"       value="<?= $door ?>" type="hidden" />
  <input name="ent_side"   value="<?= $ent_side ?>" type="hidden" />
  <input name="laminate"   value="<?= $laminate ?>" type="hidden" />
  <input name="additional_scratch" value="<?= $additional_scratch ?>" type="hidden" />

  <fieldset>
    <label for="email">Email</label>
    <input id="email" name="email" value="foo@bar.com" />
  </fieldset>
  <fieldset>
    <label for="address_1">Address Line 1</label>
    <input id="address_1" name="address_1" value="1234 memory lane" />
  </fieldset>
  <fieldset>
    <label for="address_2">Address Line 2</label>
    <input id="address_2" name="address_2" value="" />
  </fieldset>
  <fieldset>
    <label for="city">City</label>
    <input id="city" name="city" value="Seattle" />
  </fieldset>
  <fieldset>
    <label for="state">State</label>
    <input id="state" name="state" value="WA" />
  </fieldset>
  <fieldset>
    <label for="zip">Zip</label>
    <input id="zip" name="zip" value="98177" />
  </fieldset>

  <script
    src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
    data-key="<?= $STRIPE_PUBLISHABLE_KEY ?>"
    data-amount="<?= $price ?>"
    data-name="Moderistcat"
    data-description="<?= $description ?>"
    data-image="http://placekitten.com/128/128">
  </script>
</form>