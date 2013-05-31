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
      <label for="name">Your Name</label>
      <input id="name" name="name" value="" />
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

  <button class="stripe-button-el" type="submit"><span style="display: block; min-height: 30px;">Pay with Card</span></button>

</form>
</div>
<script src="https://checkout.stripe.com/v2/checkout.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script>
$(document).ready(function(){
  $('#name').focus();
  function validate() {
    $('input').removeClass('error');
    var valid = true,
        nonBlank = ['name', 'email', 'address_1', 'city', 'state', 'zip'],
        input;
    for (var i=0; i<nonBlank.length; i++) {
      input = $('#' + nonBlank[i]);
      if (input.val().length === 0) {
        valid = false;
        input.addClass('error');
      }
    }
    input = $('#email');
    // Email must have an '@' character. Can't be in first or last
    if (input.val().indexOf('@') <= 0 ||
        input.val().indexOf('@') + 1 === input.val().length ) {
      valid = false;
      input.addClass('error');
    }
    input = $('#zip');
    var zip = parseInt(input.val(), 10);
    if (isNaN(zip)) {
      valid = false;
      input.addClass('error');
    }
    return valid;
  }

  // A couple things must go right...
  // Form must be valid
  // We must have already retrived the Stripe token
  // We achieve this by submitting the form atleast twice and canelling
  // along the way.
  var stripeSubmited = false;
  $('#payment-form').submit(function(e) {
    if (validate() === false) {
      e.preventDefault();
      return false;
    }

    if (stripeSubmited === false) {
      e.preventDefault();
      var token = function(res){
        stripeSubmited = true;
        var $input = $('<input type=hidden name=stripeToken />').val(res.id);
       $('form').append($input).submit();
      };

      StripeCheckout.open({
        key:         '<?= $STRIPE_PUBLISHABLE_KEY ?>',
        address:     false,
        amount:      <?= $total_price * 100 ?>,
        currency:    'usd',
        name:        'Modernistcat',
        description: '<?= htmlspecialchars($stripe_description) ?>',
        panelLabel:  'Checkout',
        image:       "/<?= $productDb->images[0] ?>",
        token:       token
      });
      return false;
    } else {
      // Form must be valid and Stripe magick is ready
      return true;
    }
  });
});
  </script>
<?php include 'footer.php'; ?>