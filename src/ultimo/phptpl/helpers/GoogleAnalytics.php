<?php

namespace ultimo\phptpl\helpers;

class GoogleAnalytics extends \ultimo\phptpl\Helper {
  public function __invoke($account) {
    return "<script type=\"text/javascript\">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '{$account}']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>";
  }
}

