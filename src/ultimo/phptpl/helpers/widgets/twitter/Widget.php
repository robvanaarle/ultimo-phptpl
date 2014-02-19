<?php

namespace ultimo\phptpl\helpers\widgets\twitter;

/**
 * http://twitter.com/about/resources/widgets/widget_profile
 */
abstract class Widget extends \ultimo\phptpl\helpers\widgets\Widget {
  const JAVASCRIPT_URL = 'http://widgets.twimg.com/j/2/widget.js';
  
  static private $twitterWidgetCounter = 0;

  protected $twitterWidgetId;
  
  /**
   * Constructor.
   * @param \ultimo\phptpl\Engine $engine The engine the widget is for.
   * @param string $widgetId The id of the widget.
   * @param array $attrs The attributes of the widget.
   */
  public function __construct(\ultimo\phptpl\Engine $engine, $widgetId, array $attrs = array()) {
    parent::__construct($engine, $widgetId, $attrs);
    self::$twitterWidgetCounter++;
    $this->twitterWidgetId = 'twtr-widget-' . self::$twitterWidgetCounter;
  }
  
  public function getDefaultAttrs() {
    return array(
        //'id' => 'test',
        'type' => null,
        'version' => 2,
        'rrp' => 30,
        'interval' => 30000,
        'width' => 250,              // auto
        'height' => 300,
        'subject' => '',
        'title' => '',
        'theme' => array(
            'shell' => array(
                'background' => '#8ec1da', // if boxed=false, this will become transparent
                'color' => '#ffffff'
            ),
            'tweets' => array(
                'background' => '#ffffff',
                'color' => '#444444',
                'links' => '#1985b5'
            )
        ),
        'features' => array(
            'scrollbar' => true,
            'loop' => true,
            'live' => true,
            'behavior' => 'all',      // all, default
            'avatars' => true,
            'fullscreen' => false,
            'dateformat' => 'relative', // relative, absolute
            
            // present in source, but does not work?
            'hashtags' => false,
            'timestamp' => true
        ),
        
        // attributes specified after rendering
        'user' => null, // setUser(), or setList() (if list is defined)
        'list' => null,  // setList()
        
        // custom attributes
        'boxed' => true
    );
  }
  
  public final function render() {
    $this->engine->headScript()->appendJavascriptFile(self::JAVASCRIPT_URL);
    
    $attrs = $this->attrs;
    $user = $attrs['user'];
    unset($attrs['user']);
    $list = $attrs['list'];
    unset($attrs['list']);
    $boxed = $attrs['boxed'];
    unset($attrs['boxed']);
    
    if (!$boxed) {
      $attrs['theme']['shell']['background'] = 'transparent';
      $this->engine->headStyle()->appendCss(
              "#{$this->twitterWidgetId} .twtr-hd, #{$this->twitterWidgetId} .twtr-ft {\n" .
                "display: none;\n" .
              "}");
    }
    $html = '<script type="text/javascript">' . "\n";
    $html .= 'new TWTR.Widget(' . json_encode($attrs) . ').render()';
    
    if ($user !== null) {
      $user = addcslashes($user, "'");
      
      if ($list !== null) {
        $list = addcslashes($list, "'");
        $html .= ".setList('{$user}', '{$list}')";
      } else {
        $html .= ".setUser('{$user}')";
      }
    }
    
    $html .= ".start();\n</script>";
    return $html;
  }
}

/*

search
title
subject

PROFILE
=======
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 30000,
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#333333',
      color: '#ffffff'
    },
    tweets: {
      background: '#000000',
      color: '#ffffff',
      links: '#4aed05'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: true,
    behavior: 'all'
  }
}).render().setUser('di_visie').start();

SEARCH
======
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: 'rainbow',
  interval: 30000,
  title: 'It\'s a double rainbow',
  subject: 'Across the sky',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#8ec1da',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#1985b5'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: true,
    behavior: 'default'
  }
}).render().start();
 
LIST
====
new TWTR.Widget({
  version: 2,
  type: 'list',
  rpp: 30,
  interval: 30000,
  title: 'Everything we do at',
  subject: 'the twoffice',
  width: 'auto',
  height: 300,
  theme: {
    shell: {
      background: '#ff96e7',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#b740c2'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    behavior: 'all'
  }
}).render().setList('twitter', 'd9').start();


FAVES
=====
<script>
new TWTR.Widget({
  version: 2,
  type: 'faves',
  rpp: 10,
  interval: 30000,
  title: 'The best of Twitter according to',
  subject: 'Top Tweets',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#43c43f',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#43c43f'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    behavior: 'all'
  }
}).render().setUser('toptweets').start();
</script>

 */