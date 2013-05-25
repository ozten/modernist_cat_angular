<?
/**
 * Calculates price based on inputs. Depends on Option and Addon validation happening elsewhere
 */
function calculate_total_price($product_id, $productDb, $optionsDb, $addonsSelected, $feederSize) {
    $total_price = 0;
    if ($productDb->price) {
        $total_price += $productDb->price;
    } else {
        die('Produce database error, no price');
    }

    // Feeders size affect price
    if ($product_id == 'feeder') {
        $priceCode = array_search($feederSize, $optionsDb->size->choices);
        $total_price = $optionsDb->size->price[$priceCode];
    }

    foreach($addonsSelected as $addon => $value) {
        if ($value == 'true') {
            if ($productDb->addons->{$addon}) {
                $total_price += $productDb->addons->{$addon}->price;
            }
        }
    }
    return $total_price;
}

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

// Default size for feeder... irrelevant for other products
$feederSize = 'Double';

// Feeders size affect price
if ($product_id == 'feeder') {
    if ($options && $options->asize) {
        if (in_array($options->asize, $optionsDb->size->choices)) {
	    $feederSize = $options->asize;
        } else {
   	    die('Invalid size, invalid option');
        }
    }
}



// Decode this one as an array instead of an Object
// to make looping over keys easier

// addonsSelected:{"elounge":true}
$addonsSelected = json_decode($incomingAddonsSelected, TRUE);

//  addonValues:{"elounge":"Chipper Stone"} 
$addonValues = json_decode($incomingAddonValues, TRUE);

$addonSelections = array();
foreach($addonsSelected as $addon => $value) {
    if ($value == TRUE) {
        if ($productDb->addons->{$addon}) {
	       $addonKey = $productDb->addons->{$addon}->options;
         
           if (is_null($addonKey)) {
              // Do nothing, this is for addons like litterpan
              $addonKey = $addon;
              $addonValue = 'selected';
           } else {
              // A valid selection!
              $addonValue = $addonValues[$addon]; 
              if (in_array($addonValue, $optionsDb->{$addonKey}->choices) == FALSE) {
                 die('Invalid Addon choice, <?= $addon => cannot be $value for <?= $product_id product =>');
              }
           }
           array_push($addonSelections, array($addon, $addonValue));   

        } else {
            die('Invalid addon $addon is not valid for $product_id product');
        }
    }
}

// Validate Options and Addons which don't affect the price

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

$total_price = calculate_total_price($product_id, $productDb, $optionsDb, $addonsSelected, $feederSize);
$stripe_description = $productDb->name . ' ' . $productDb->subtitle . ' total: $' . $total_price;


?>