

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript" src="https://content.jwplatform.com/libraries/0P4vdmeO.js"></script>
  	<script type="text/javascript">jwplayer.key="rqQQ9nLfWs+4Fl37jqVWGp6N8e2Z0WldRIKhFg==";</script>

    <?php if ($this->config['netflix_skin'] == 1): ?>
        <link href="<?=getThemeURI()?>/assets/css/netflix-skin.css" rel="stylesheet"/>
    <?php endif; ?>



    <?php if ($file['type'] == 'GPhoto'): ?>
      <meta name="referrer" content="never" /><meta name="referrer" content="no-referrer" />
      <link rel='dns-prefetch' href='//lh3.googleusercontent.com' />
    <?php endif; ?>



    <style media="screen">
      body{
        padding: 0;
        margin: 0;
      }
      #jwplayer {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: auto !important;
        height: auto !important;
}
    </style>



    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/ufilestorage/a@master/jquery-2.2.3.min.js"></script>
   </head>

  <body>

    <div id="jwplayer"></div>





    <?php


$logo = PROOT . '/uploads/' . $this->config['player_logo'];

$script = 'const playerInstance = jwplayer("jwplayer").setup({
  playlist: [{
      title: "One Playlist Item With Multiple Qualities",
      sources: '.$sources.',
      "image": "'.$thumb.'",
      "tracks": '.$subtitles.'
  }],
  "advertising": {
    "client": "vast",
    "schedule": '.$this->config['vastAds'].'
  },
  "logo": {
   "file": "'.$logo.'",
   "link": "#",
   "hide": "false",
   "position": "top-right"
 },
 skin: {
   name: "netflix"
 }

  });




';

if ($this->config['netflix_skin'] == 1) {
  $script .= '  playerInstance.on("ready", function () {
    // Move the timeslider in-line with other controls
    const playerContainer = playerInstance.getContainer();
    const buttonContainer = playerContainer.querySelector(".jw-button-container");
    const spacer = buttonContainer.querySelector(".jw-spacer");
    const timeSlider = playerContainer.querySelector(".jw-slider-time");
    buttonContainer.replaceChild(timeSlider, spacer);
  });';
}

     ?>




    <script>
    <?php
       error_reporting(E_ALL);
       $packer = new JSPacker($script, 'Normal', true, false, true);
       $packed_js = $packer->pack();
       echo $packed_js; ?></script>

       <?=Main::unsanitized($this->config['popAds'])?>


  </body>
</html>
