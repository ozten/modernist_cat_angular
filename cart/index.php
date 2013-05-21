
<?php include 'header.php'; ?>

<?

require_once('./config.php');

// Load Product databse
// Validate inputs
$product_id = $_GET['product_id'];
$options = $_GET['options'];
$addonsSelected = $_GET['addonsSelected'];
$addonValues = $_GET['addonValues'];

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
<div class="six columns">
  <?= $product_id ?>
  <?= $options ?>
  <?= $addonsSelected ?>
  <?= $addonValues ?>
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
    data-amount="<?= $price ?>"
    data-name="Modernistcat"
    data-description="<?= $description ?>"
    data-image="http://placekitten.com/128/128">
  </script>
</form>
</div>
<?php include 'footer.php'; ?>