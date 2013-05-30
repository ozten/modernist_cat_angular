<?php include 'header.php'; ?>

<?
error_reporting(E_ALL ^E_DEPRECATED);

require_once('./config.php');

// Validate product id
// TODO check no '/' exist...
$product_id = $_GET['product_id'];
$options = json_decode($_GET['options']);
$incomingAddonsSelected = $_GET['addonsSelected'];
$incomingAddonValues = $_GET['addonValues'];
require_once('./cart.php');

?>
<form action="checkout.php" method="POST" id="payment-form">
<div class="six columns" id="checkout">

 <h5> <?= $productDb->name ?> </h5>
 <div class="subtitle"><?= $productDb->subtitle ?> | $<?= $total_price ?></div>
<ul class="no-bullet">

  <input name="product_id" value="<?= htmlspecialchars($product_id) ?>" type="hidden" />
  <input name="options" value="<?= htmlspecialchars($_GET['options']) ?>" type="hidden" />

  <input name="addonsSelected" value="<?= htmlspecialchars($_GET['addonsSelected']) ?>" type="hidden" />
  <input name="addonValues" value="<?= htmlspecialchars($_GET['addonValues']) ?>" type="hidden" />

  <? foreach($options as $option => $value) { 
    $optionTitle = $productDb->options->{$option}->title; ?>
    <li><?= $optionTitle ?>: <?= $value ?></li>
  <? } ?>

  <? foreach($addonSelections as $selection) { 
    // A $selection is [key, value] Example: ['carpet', 'Brown']
        $addonTitle = $selection[2];
        $addonValue = $selection[1];
        ?>
        <li><?= $addonTitle ?>: <?= $addonValue ?></li>
  <? } ?>
        <li><br/><a href="#" onclick="history.go(-1)">Modify Selections</a></li>
</ul>

<img src="../<?= $productDb->images[0]?>" />
</div> 
<div class="six columns" id="checkout-form"> 
  <fieldset>
    <legend>Contact Info</legend>
  <ul class="form">
      <li>
      <label for="email">Your Name</label>
      <input id="email" name="name" value="" />
      </li>
      <li>
      <label for="email">Email</label>
      <input id="email" name="email" value="" />
      </li>
   </ul>
   </fieldset>   
   <fieldset>
    <legend>Shipping Address</legend> 
    <ul class="form">
      <li> 
      <label for="address_1">Address Line 1</label>
      <input id="address_1" name="address_1" value=""/>
      </li>
      <li>
      <label for="address_2">Address Line 2</label>
      <input id="address_2" name="address_2" value=""  />
      </li>
      <li class="six columns">
      <label for="city">City</label>
      <input id="city" name="city" value="" />
      </li>
      <li class="two columns">
      <label for="state">State</label>
      <input id="state" name="state" value=""  />
      </li>
      <li class="four columns last">
      <label for="zip">Zip</label>
      <input id="zip" name="zip" value="" />
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
    data-description="<?= htmlspecialchars($stripe_description) ?>"
    data-image="/<?= $productDb->images[0] ?>">
  </script>
</form>
</div>
<?php include 'footer.php'; ?>