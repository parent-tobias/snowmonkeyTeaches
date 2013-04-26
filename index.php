<?php
 /********
  * Flickr.com feed tutorial, step 1.
  * v 0.0.1, 04/23/2013 -- Tobias Parent (http://snowmonkey.koding.com/)
  * 
  *******/

#
# build the API URL to call
#
if($_GET['pageno']){
  $pageNo = $_GET['pageno'];
} else {
  $pageNo = 1;
};

$params = array(
  'api_key'	 => '[YOUR API KEY GOES HERE]',
  'method'	 => 'flickr.interestingness.getList',
  'format'	 => 'php_serial',
  'per_page' => '10',
  'page'     => $pageNo,
);

$encoded_params = array();

foreach ($params as $k => $v){

	$encoded_params[] = urlencode($k).'='.urlencode($v);
}


#
# call the API and decode the response
#

$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

$rsp = file_get_contents($url);

$rsp_obj = unserialize($rsp);


#
# display the photo title (or an error if it failed)
#

if ($rsp_obj['stat'] == 'ok'){
?>
<!DOCTYPE html>
<html lang="en/us">
  <head>
    <title>Flickr Feed Tutorial--second draft</title>
    <script type="text/javascript" src="./assets/js/vendor/jquery-1.9.1.js" ></script>
    <script type="text/javascript" src="./assets/js/vendor/underscore.js"></script>
    <link rel="stylesheet" href="./assets/css/style.css" />
    
    <script type="text/javascript">
      var flickrUrlRoot = "http://api.flickr.com/services/rest/?jsoncallback=?",
          totalPages = <? echo $rsp_obj['photos']['pages']; ?>,
          flickrConfig = {
            api_key:    "<? echo $params['api_key'] ?>",
            method:     "<?echo $params['method'] ?>",
            format:     "json",
            per_page:   "<? echo $params['per-page'] ?>",
            page:       "<? echo $rsp_obj['photos']['page']+1; ?>"
          };
    </script>
  </head>
  <body>
    <hgroup>
      <h1>Tutorial, Second revision</h1>
      <h2>Clicking "More..." will call the API and append more images</h2>
    </hgroup>
    <p>In this revision, the page is initially returned with a set of populated images by PHP, but all subsequent calls are done via AJAX.</p>
    <section class="pros">
      <h1>Pros</h1>
      <p>Far less data is being passed, and the page isn't being re-rendered every time, which should speed things up considerably. Also, because the AJAX call is going to a remote server (rather than mine), server load is decreased.</p>
    </section>
    <section class="cons">
      <h1>Cons</h1>
      <p>...</p>
    </section>
    <div class="image-listing">
    <?php foreach( $rsp_obj['photos']['photo'] as $photo){ ?>
      <img src='http://farm<? echo $photo["farm"]; ?>.staticflickr.com/<? echo $photo["server"]; ?>/<? echo $photo["id"]; ?>_<? echo $photo["secret"] ?>_s.jpg' />
    <?php }; ?>
    </div>
    <nav>
      <span class="page-count-status">Page <? echo $rsp_obj['photos']['page']; ?> of <? echo $rsp_obj['photos']['pages']; ?> 
      <?php if($rsp_obj['photos']['page'] !== $rsp_obj['photos']['pages']){ ?>
        <button class="next-set">Get more photos</button>
      <? } ?>
    </nav>
  </body>
</html>
<?
}else{

	echo "Call failed!";
}

?>
