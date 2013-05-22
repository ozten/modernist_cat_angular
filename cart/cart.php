<?
/**
 * Calculates price based on inputs. Depends on Option and Addon validation happening elsewhere
 */
function calcualte_total_price($product_id, $productDb, $optionsDb, $addonsSelected, $feederSize) {
    $total_price = 0;
    if ($productDb->price) {
        $total_price += $productDb->price;
    } else {
        die('Produce database error, no price');
    }

    // Feeders size affect price
    if ($product_id == 'feeder') {
        $priceCode = array_search($feederSize, $optionsDb->size->choices);
        $total_price += $optionsDb->size->price[$priceCode];
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

?>