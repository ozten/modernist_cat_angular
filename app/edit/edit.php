    

    <?php
    class Slide {
    public $image = "";
    public $title = "";
    public $caption = "";
    public $product = "";
    }
     
    $slide = new Slide();
    $slide->image = "/img/homepage/1A.jpg";
    $slide->title = "NEW IMAGE";
    $slide->caption = "This is a caption";
    $slide->product = "console"
     
    // Returns: {"firstname":"foo","lastname":"bar"}
    json_encode($slide);
     
  
     
    /* Returns:
      {
      "image": "img/homepage/1A.jpg",
      "title": "Circa50:Console",
      "caption":"",
      "product": "console"
   
      }
    */


    $fp = fopen('results.json', 'w');
	fwrite($fp, json_encode($slide));
	fclose($fp);

	$rawJson = file_get_contents('results.json');
	$editDb = json_decode($rawJson);
    ?>
   