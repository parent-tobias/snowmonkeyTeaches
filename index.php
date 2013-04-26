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
  'api_key'	 => '17136ae0b22d491a9ff97bc371aa8536',
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
    <title>Flickr Feed Tutorial--first draft</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
  </head>
  <body>
    <hgroup>
      <h1>Tutorial, First revision</h1>
      <h2>Each click of next/previous re-renders everything</h2>
    </hgroup>
    <p>In this, the first draft of a evolution series, a set of images are being fetched from flickr.com, their 'interestingness' collection. It's being fetched in blocks of ten, and clicking on previous/next below results in a call to PHP, which fetches the entire page, showing the next/previous ten images.</p>
    <section class="pros">
      <h1>Pros</h1>
      <p>This is the traditional model, call-and-response. Works fine, and allows the back end to have more control over what the front end can access. Really can't think of many advantages to this.</p>
    </section>
    <section class="cons">
      <h1>Cons</h1>
      <p>Server load, to begin with -- while this is a small example, and it isn't storing data on my server, it calls flickr, parses the feed and outputs the data, which does result in a server hit each time.</p>
      <p>Non-intuitive to modern users. Rather than re-rendering everything, we could simply let javascript do the work and get more images. Like, for example, <a href="../step_02/">this</a>.</p>
    </section>    <div class="image-listing">
    <?php foreach( $rsp_obj['photos']['photo'] as $photo){ ?>
      <img src='http://farm<? echo $photo["farm"]; ?>.staticflickr.com/<? echo $photo["server"]; ?>/<? echo $photo["id"]; ?>_<? echo $photo["secret"] ?>_s.jpg' />
    <?php }; ?>
    </div>
    <nav>
    <?php if($rsp_obj['photos']['page'] != 1){ ?>
      <a href="./index.php?pageno=<? echo $rsp_obj['photos']['page']-1; ?>"><<</a>
    <? } ?>
    Page <? echo $rsp_obj['photos']['page']; ?> of <? echo $rsp_obj['photos']['pages']; ?>
    <?php if($rsp_obj['photos']['page'] != $rsp_obj['photos']['pages']){ ?>
      <a href="./index.php?pageno=<? echo $rsp_obj['photos']['page']+1; ?>">>></a>
    <? } ?>
    </nav>
  </body>
</html>
<?
}else{

	echo "Call failed!";
}

?>