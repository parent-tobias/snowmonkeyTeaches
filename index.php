
<!DOCTYPE html>
<html lang="en/us">
  <head>
    <title>Flickr Feed Tutorial--third draft</title>
    <script type="text/javascript" src="./assets/js/vendor/jquery-1.9.1.js" ></script>
    <script type="text/javascript" src="./assets/js/vendor/underscore.js"></script>
    <link rel="stylesheet" href="./assets/css/style.css" />
    
    <script type="text/javascript">
      var flickrUrlRoot = "http://api.flickr.com/services/rest/?jsoncallback=?",
          totalPages,
          flickrConfig = {
            api_key:    "[YOUR API KEY GOES HERE],
            method:     "flickr.interestingness.getList,
            format:     "json",
            per_page:   "10",
            page:       1
          };
          
      // When the document has loaded, we can attach the listener to the button,
      //  and set its processing
      $(document).ready(function(){
        fetchMorePhotos();
        $('.next-set').on("click", function(){
          fetchMorePhotos();
        });
        
        function fetchMorePhotos(){
          // Moving the 'Fetch more photos' routine out of the event handler, as it will
          //   need to be called in more places: we'll call it on init, and on the click.
          //   In both cases, the functionality will be identical: fetch the url with the
          //   config object, append the images to the pane, update the config object.

          var imagePane = $('.image-listing');

          $.getJSON(flickrUrlRoot, flickrConfig, function(data){
            // If we're here, we have a dataset. Parse it and populate.
            _.each(data.photos.photo, function(photo){
              // Create the image. The URL isn't given, but we can build it.
              var thisImg = $("<img>").attr("src", "http://farm"+photo.farm+".staticflickr.com/"+photo.server+"/"+photo.id+"_"+photo.secret+"_s.jpg");
              imagePane.append(thisImg);
            });
            // In addition to adding the images to the appropriate pane, we also
            //  have to update the page count beside the 'Get more photos'
            $(".page-count-status").text("Page "+flickrConfig.page+" of "+totalPages);
            
            // And also update the page number that will be passed to the API
            flickrConfig.page = Number(flickrConfig.page)+1;
          })
        }
      
      });
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

    </div>
    <nav>
      <span class="page-count-status">Page <? echo $rsp_obj['photos']['page']; ?> of <? echo $rsp_obj['photos']['pages']; ?> 
      <?php if($rsp_obj['photos']['page'] !== $rsp_obj['photos']['pages']){ ?>
        <button class="next-set">Get more photos</button>
      <? } ?>
    </nav>
  </body>
</html>
