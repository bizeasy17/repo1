<?php

/**
 * 
 * GK Social Widget class
 *
 **/

class GK_Weather_Widget extends WP_Widget {
	var $config;
	var $content;
	var $error;
	var $icons;
	var $parsedData;
    var $translation;
    var $cond_tmp;
	
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function GK_Weather_Widget() {
		$this->WP_Widget(
			'widget_gk_weather', 
			__( 'GK Weather', GKTPLNAME ), 
			array( 
				'classname' => 'widget_gk_weather', 
				'description' => __( 'Use this widget to show weather forecast', GKTPLNAME) 
			),
			array(
				'width' => 320, 
				'height' => 300
			)
		);
		
		$this->alt_option_name = 'widget_gk_weather';
	}

	/**
	 *
	 * Outputs the HTML code of this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void
	 *
	 **/
	function widget($args, $instance) {
		$cache = wp_cache_get('widget_gk_weather', 'widget');
		
		if(!is_array($cache)) {
			$cache = array();
		}

		if(!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		if(isset($cache[$args['widget_id']])) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		//
		extract($args, EXTR_SKIP);
		//
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		// get settings
		$this->config['fullcity'] = empty($instance['fullcity']) ? 'Warsaw' : $instance['fullcity'];
		$this->config['showCity'] = empty($instance['showCity']) ? 'on' : $instance['showCity'];
		$this->config['showHum'] = empty($instance['showHum']) ? 'on' : $instance['showHum'];
		$this->config['showWind'] = empty($instance['showWind']) ? 'on' : $instance['showWind'];
		$this->config['tempUnit'] = empty($instance['tempUnit']) ? 'c' : $instance['tempUnit'];
		$this->config['showPresent'] = empty($instance['showPresent']) ? 'on' : $instance['showPresent'];
		$this->config['nextDays'] = empty($instance['nextDays']) ? 'on' : $instance['nextDays'];
		$this->config['current_icon_size'] = empty($instance['current_icon_size']) ? '64' : $instance['current_icon_size'];
		$this->config['forecast_icon_size'] = empty($instance['forecast_icon_size']) ? '32' : $instance['forecast_icon_size'];
		$this->config['WOEID'] = empty($instance['WOEID']) ? '523920' : $instance['WOEID'];
        $this->config['t_offset'] = empty($instance['t_offset']) ? '0' : $instance['t_offset'];
        $this->config['iconset'] = empty($instance['iconset']) ? 'meteocons_light' : $instance['iconset'];
		//
		if ($fb_link !== '' || $twitter_link !== '' || $gplus_link !== '' || $rss_link !== '') {
			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
			//
			$this->init();
			
			$this->getData();
			
			try{
			    $this->parseData();    
			} catch (Exception $e) {
				// use backup
			    $this->useBackup();
			    // parse the backup data
			    $this->parseData();
			}
			// render the layout
			$this->renderLayout();
			// 
			echo $after_widget;
		}
		// save the cache results
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_gk_weather', $cache, 'widget');
	}
	
	function init() {
		// error text
		$this->error = '';
		// icons array
		$this->icons = array(
            "0"                                  => array('other.png'),
            "1"                                  => array('storm.png','storm_night.png'),
            "2"                                  => array('storm.png','storm_night.png'),
            "3"                                  => array('chance_of_storm.png','chance_of_storm_night.png'),
            "4"                                  => array('thunderstorm.png'),          
            "5"                                  => array('rain_and_snow.png'),
            "6"                                  => array('sleet.png'),
            "7"                                  => array('sleet.png'),     
            "8"                                  => array('rain.png'),    
            "9"                                  => array('rain.png'),     
            "10"                                 => array('rain.png'),
            "11"                                 => array('rain.png'),
            "12"                                 => array('rain.png'),
            "13"                                 => array('chance_of_snow.png', 'chance_of_snow_night.png'),                               
            "14"                                 => array('snow.png'),
            "15"                                 => array('snow.png'),
            "16"                                 => array('snow.png'),
            "17"                                 => array('chance_of_storm.png','chance_of_storm_night.png'),  
            "18"                                 => array('rain.png'),
            "19"                                 => array('dusty.png'),
            "20"                                 => array('foggy.png','foggy_night.png'),
            "21"                                 => array('hazy.png','hazy_night.png'),
            "22"                                 => array('smoke.png','smoke_night.png'),
            "23"                                 => array('cloudy.png'),
            "24"                                 => array('cloudy.png'),      
            "25"                                 => array('snow.png'),
            "26"                                 => array('cloudy.png'),
            "27"                                 => array('mostly_cloudy.png','mostly_cloudy_night.png'), 
            "28"                                 => array('mostly_cloudy.png','mostly_cloudy_night.png'), 
            "29"                                 => array('partly_cloudy.png','partly_cloudy_night.png'),
            "30"                                 => array('partly_cloudy.png','partly_cloudy_night.png'),
            "31"                                 => array('sunny.png','sunny_night.png'),
            "32"                                 => array('sunny.png','sunny_night.png'),
            "33"                                 => array('sunny.png','sunny_night.png'),
            "34"                                 => array('partly_cloudy.png','partly_cloudy_night.png'),
            "35"                                 => array('thunderstorm.png'),
            "36"                                 => array('sunny.png','sunny_night.png'),
            "37"                                 => array('thunderstorm.png'),
            "38"                                 => array('chance_of_storm.png','chance_of_storm_night.png'),
            "39"                                 => array('chance_of_storm.png','chance_of_storm_night.png'),
            "40"                                 => array('rain.png'),
            "41"                                 => array('snow.png'),
            "42"                                 => array('snow.png'),
            "43"                                 => array('snow.png'),
            "44"                                 => array('partly_cloudy.png','partly_cloudy_night.png'),
            "45"                                 => array('chance_of_storm.png','chance_of_storm_night.png'),
            "46"                                 => array('chance_of_snow.png', 'chance_of_snow_night.png'),
            "47"                                 => array('chance_of_storm.png','chance_of_storm_night.png'),
            "3200"                               => array('other.png')
 		);
        // translation table
        $this->translation = array(
            "Tornado"                           => __('Tornado', GKTPLNAME),
            "Tropical Storm"                    => __('Tropical storm', GKTPLNAME),
            "Hurricane"                         => __('Hurricane', GKTPLNAME),
            "Severe Thunderstorms"              => __('Severe Thunderstorms', GKTPLNAME),
            "Thunderstorms"                     => __('Thunderstorms', GKTPLNAME),
            "Mixed Rain and Snow"               => __('Mixed Rain and Snow', GKTPLNAME),
            "Mixed Rain and Sleet"              => __('Mixed Rain and Sleet', GKTPLNAME),
            "Mixed Snow and Sleet"              => __('Mixed Snow and Sleet', GKTPLNAME),
            "Freezing Drizzle"                  => __('Freezing Drizzle', GKTPLNAME),
            "Drizzle"                           => __('Drizzle', GKTPLNAME),
            "Freezing Rain"                     => __('Freezing Rain', GKTPLNAME),
            "Showers"                           => __('Showers', GKTPLNAME),
            "Snow Flurries"                     => __('Snow Flurries', GKTPLNAME),
            "Light Snow Showers"                => __('Light Snow Showers', GKTPLNAME),
            "Blowing Snow"                      => __('Blowing Snow', GKTPLNAME),
            "Snow"                              => __('Snow', GKTPLNAME),
            "Hail"                              => __('Hail', GKTPLNAME),
            "Sleet"                             => __('Sleet', GKTPLNAME),
            "Dust"                              => __('Dust', GKTPLNAME),
            "Foggy"                             => __('Foggy', GKTPLNAME),
            "Haze"                              => __('Haze', GKTPLNAME),
            "Smoky"                             => __('Smoky', GKTPLNAME),
            "Blustery"                          => __('Blustery', GKTPLNAME),
            "Windy"                             => __('Windy', GKTPLNAME),
            "Cold"                              => __('Cold', GKTPLNAME),
            "Cloudy"                            => __('Cloudy', GKTPLNAME),
            "Mostly Cloudy"                     => __('Mostly Cloudy', GKTPLNAME),
            "Partly Cloudy"                     => __('Partly Cloudy', GKTPLNAME),
            "Clear"                             => __('Clear', GKTPLNAME),
            "Sunny"                             => __('Sunny', GKTPLNAME),
            "Fair"                              => __('Fair', GKTPLNAME),
            "Mixed Rain and Hail"               => __('Mixed Rain and Hail', GKTPLNAME),
            "Hot"                               => __('Hot', GKTPLNAME),
            "Isolated Thunderstorms"            => __('Isolated Thunderstorms', GKTPLNAME),
            "Scattered Thunderstorms"           => __('Scattered Thunderstorms', GKTPLNAME),
            "Scattered Showers"                 => __('Scattered Showers', GKTPLNAME),
            "Scattered Snow Showers"            => __('Scattered Snow Showers', GKTPLNAME),
            "Heavy Snow"                        => __('Heavy Snow', GKTPLNAME),
            "Partly Cloudy"                     => __('Partly Cloudy', GKTPLNAME),
            "Thundershowers"                    => __('Thundershowers', GKTPLNAME),
            "Snow Showers"                      => __('Snow Showers', GKTPLNAME),
            "Isolated thundershowers"           => __('Isolated thundershowers', GKTPLNAME),
            "Not Available"                     => __('Not Available', GKTPLNAME),
            "Mostly Clear"                      => __('Mostly Clear', GKTPLNAME),
            "Light Rain"						=> __('Light rain', GKTPLNAME),
            "Fog"								=> __('Fog', GKTPLNAME),
            "Thunder"							=> __('Thunder', GKTPLNAME),
            "Mist"								=> __('Mist', GKTPLNAME),
            "Rain Shower"						=> __('Rain Shower', GKTPLNAME),
            "Light Rain Showers"				=> __('Light Rain Showers', GKTPLNAME),
            "Rain/Snow Showers"					=> __('Rain/Snow Showers', GKTPLNAME),
            "PM Rain/Snow Showers"				=> __('PM Rain/Snow Showers', GKTPLNAME),
            "Light Snow"						=> __('Light Snow', GKTPLNAME),
            "Snow Showers Late"					=> __('Snow Showers Late', GKTPLNAME)
        );
        
        // parsed from XML data
		$this->parsedData = array(
			'unit' => '',
			'current_condition' => '',
			'current_temp_f' => '',
			'current_temp_c' => '',
			'current_humidity' => '',
			'current_icon' => '',
			'current_wind' => '',
            'sunrise' => '',
            'sunset' => '',
			'forecast' => array()
		);
	}
	
	function getData() {
		if(get_transient(md5($this->id . '_content')) == false) {
			if(function_exists('curl_init')) {
				// initializing connection
				$curl = curl_init();
				// saves us before putting directly results of request
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
				// url to get
				curl_setopt($curl, CURLOPT_URL, 'http://weather.yahooapis.com/forecastrss?w='.$this->config['WOEID']."&u=".$this->config['tempUnit']); 
				// timeout in seconds
				curl_setopt($curl, CURLOPT_TIMEOUT, 20);
				// useragent
				curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				// reading content
				$this->content = curl_exec($curl);
				set_transient(md5($this->id . '_content') , $this->content, 15 * 60);
				// closing connection
				curl_close($curl);
			} 
	        // check file_get_contents function enable and allow external url's'
	        else if( file_get_contents(__FILE__) && ini_get('allow_url_fopen') && !function_exists('curl_init')) {
	            $this->content = file_get_contents('http://weather.yahooapis.com/forecastrss?w='.$this->config['WOEID']."&u=".$this->config['tempUnit']);
	        } else {
				$this->error = 'cURL extension and file_get_content method is not available on your server';
			}
		} else {
			$this->content = get_transient(md5($this->id . '_content'));
		}
	}
	
	function parseData() {
		if($this->error === '') {
	        // checking for 400 Bad request page
			if(strpos($this->content, '400 Bad') == FALSE) {	
                $this->content = str_replace('yweather:','', $this->content);
                $this->content = str_replace('geo:','', $this->content);
                // load the XML content
				if($this->content == '') {
					$this->useBackup();
				}
				
				$xml = simplexml_load_string($this->content);
				
                if($xml !== FALSE) {
                   	$xml_attrs = (array) $xml->channel->description->attributes();
                   	$xml_attrs = $xml_attrs['@attributes'];
                   	
                   	if(
                   		!isset($xml_attrs['date']) || 
                   		(
                   			isset($xml_attrs['date']) && 
                   			strpos($xml_attrs['date'], "Error") == FALSE
                   		)
                   	) {
						$problem = false;
	                    $current_info = $xml->channel;
	                    $current_info2 = $xml->channel->item[0];
	                    $forecast_info = $xml->channel->item[0];
	                   
	                    if(
							isset($current_info->units) &&
							isset($current_info2->condition) &&
							isset($current_info->atmosphere) &&
							isset($current_info->image) &&
	                        isset($current_info->location) &&
							isset($current_info->wind)
						) {
							$current_info2_attrs = (array) $current_info2->condition->attributes();
							$current_info2_attrs = $current_info2_attrs['@attributes'];
						
	                        // loading data from feed
	                        if(isset($this->translation[$current_info2_attrs['text']])){
								$this->parsedData['current_condition'] = $this->translation[$current_info2_attrs['text']];
	                        } else {
	                       		$this->parsedData['current_condition'] = $current_info2_attrs['text'];  
	                        }
	                        // get the attributes arrays
	                        $units_attrs = (array) $current_info->units->attributes();
	                        $units_attrs = $units_attrs['@attributes'];
	                        
	                        $atm_attrs = (array) $current_info->atmosphere->attributes();
	                        $atm_attrs = $atm_attrs['@attributes'];
	                        
	                        $wind_attrs = (array) $current_info->wind->attributes();
	                        $wind_attrs = $wind_attrs['@attributes'];
	                        
	                        $astronomy_attrs = (array) $current_info->astronomy->attributes();
	                        $astronomy_attrs = $astronomy_attrs['@attributes'];
	                        //
	                        //
							$this->parsedData['current_temp'] = $current_info2_attrs['temp']."&deg;".$units_attrs['temperature'];
							$this->parsedData['current_humidity'] = __('Humidity', GKTPLNAME) ." " .$atm_attrs['humidity']."%";
	                        $this->parsedData['current_icon'] = $current_info2_attrs['code'];
							$this->parsedData['current_wind'] = __('Wind', GKTPLNAME) ." ".$wind_attrs['speed']." ".$units_attrs['speed'];
	                        $this->parsedData['sunrise'] = $astronomy_attrs['sunrise'];
	                        $this->parsedData['sunset'] = $astronomy_attrs['sunset'];
	                        // parsing forecast
	                        for($i = 0; $i < 2; $i++) {
	                        	$forecast_attrs = (array) $forecast_info->forecast[$i]->attributes();
	                        	$forecast_attrs = $forecast_attrs['@attributes'];
	                        	
	                        	if(isset($this->translation[$forecast_attrs['text']])){
	                        		$this->cond_tmp = $this->translation[$forecast_attrs['text']];
	                        	} else {
	                        	   $this->cond_tmp = $forecast_attrs['text'];
	                        	}
	                        	$this->parsedData['forecast'][$i] = array(
									"day" => $this->translateDate($forecast_attrs['date']),
									"low" => $forecast_attrs['low']."&deg;".$units_attrs['temperature'],
									"high" => $forecast_attrs['high']."&deg;".$units_attrs['temperature'],
	                                "icon" => $forecast_attrs['code'],
									"condition" => $this->cond_tmp,
								);
							}
						} else {
							$problem = true; // set the problem 
							$this->error = 'An error occured during parsing XML data. Please try again.';
						}
						// if problem detected
						if($problem == true) {
							$this->error = 'An error occured during parsing XML data. Please try again.';
						} else {
						    // prepare a backup
						    set_transient(md5($this->id . '_backup') , $this->content, 24 * 60 * 60);
						}
					} else { // if specified location doesn't exist
						$this->error = 'An error occured - you set wrong location or data for your location are unavailable';
					}
	            } else {
					$this->error = 'Parse error in downloaded data'; // set error
				}
	        } else {
				$this->error = 'Parse error in downloaded data (400)'; // set error
			}
		}
	}
	
	function useBackup() {
	    $this->error = '';
		$this->content = get_transient(md5($this->id . '_backup'));
	}
	
	function translateDate($date) {
    	preg_match('/[A-Za-z]{3,}/', $date, $month);
     	$replace = '';
   		
    	switch($month[0]) {
    		case 'Jan' : $replace = __('Jan', GKTPLNAME); break;
    		case 'Feb' : $replace = __('Feb', GKTPLNAME); break;
    		case 'Mar' : $replace = __('Mar', GKTPLNAME); break;
    		case 'Apr' : $replace = __('Apr', GKTPLNAME); break;
    		case 'May' : $replace = __('May', GKTPLNAME); break;
    		case 'Jun' : $replace = __('Jun', GKTPLNAME); break;
    		case 'Jul' : $replace = __('Jul', GKTPLNAME); break;
    		case 'Aug' : $replace = __('Aug', GKTPLNAME); break;
    		case 'Sep' : $replace = __('Sep', GKTPLNAME); break;
    		case 'Oct' : $replace = __('Oct', GKTPLNAME); break;
    		case 'Nov' : $replace = __('Nov', GKTPLNAME); break;
    		case 'Dec' : $replace = __('Dec', GKTPLNAME); break;
    		default: $replace = $month[0];
    	}
    	
    	$date = str_replace($month[0], $replace, $date);
    	
    	return $date;
    } 

	function icon($icon, $size = 128) {
		// 
		$icon_path =  '';
		// if selected icon exists
		if(is_array($this->icons[$icon])) {
            // if user use yahoo feed
           	if ($this->config['source']=='yahoo' && isset($this->parsedData['sunrise']) && isset($this->parsedData['sunset'])){
                $sunrise = $this->prepareTime($this->parsedData['sunrise'])+$this->config['t_offset']*3600;
                $sunset = $this->prepareTime($this->parsedData['sunset'])+$this->config['t_offset']*3600;
                // flag for night ;)
				$night = false;
				// night check ;)
				if(time() < $sunrise || time() > $sunset) {
					$night = true; // now is night! :P
				}
				// getting final icon - if selected icon has two icons - for day and night - return correct icon
				return $icon_path . $this->icons[$icon][(count($this->icons[$icon]) > 1 && $night) ? 1 : 0];
            } else {
				return $icon_path . $this->icons[$icon][0];
			}
		} else { // else - return "?" icon
			return 'other';
		}
	}
	
	function temp($temp) {
		if($this->parsedData['unit'] == 'US' && $this->config['tempUnit'] == 'c') return $this->F2Cel($temp);
		else if($this->parsedData['unit'] == 'SI' && $this->config['tempUnit'] == 'f') return $this->Cel2F($temp);
		else return $temp.(($this->config['tempUnit'] == 'c') ? '&deg;C' : '&deg;F' );		
	}
	
	function prepareTime($time) {
        $f_date = date("Y-m-d")." ".$time;
        $pos = strpos($f_date, "pm");
        $f_date = preg_replace('/ [a-z][a-z]/', ':00', $f_date);
        return strtotime($f_date) + (($pos !== FALSE) ? 12*3600 : 0); // if pm add 12 hours
    }
    
    function Cel2F($value) {
    	return round(32 + ((5/9) * $value)).'&deg;F';
    }
    
    function F2Cel($value) {
    	return round((5/9) * ($value - 32)).'&deg;C';
    }

	function renderLayout() {
		echo '<div class="gkw-main">';
		
		if($this->config['showPresent'] == 'on') {
		    echo '<div class="gkw-current">';
			echo '<div class="gkw-main-left">';
			
			$iconset_color = ' light';
			if ($this->config['iconset'] == 'meteocons_dark') {
				$iconset_color = ' dark';
			}

			echo '<i class="meteocons-'.str_replace('.png', '', $this->icon($this->parsedData['current_icon'])).  $iconset_color . ' size-' . $this->config['current_icon_size']. '"></i>';
			echo '<p class="gkw-temp">'.$this->parsedData['current_temp'].'</p>';
			echo '</div>';
			echo '<div class="gkw-main-right">';
			
			if($this->config['showCity'] == 'on') {
				echo '<h2>'.$this->config['fullcity'].'</h2>';
			}
			echo '<p class="gkw-condition">'.$this->parsedData['current_condition'].'</p>';
			
			if($this->config['showHum'] == 'on') {
				echo '<p class="gkw-humidity">'.$this->parsedData['current_humidity'].'</p>';
			}
			
			if($this->config['showWind'] == 'on') {
				echo '<p class="gkw-wind">'.$this->parsedData['current_wind'].'</p>';
			}
			
			echo '</div>';
			echo '</div>';
		}
		
		if($this->config['nextDays'] == 'on') {
			echo '<ul class="gkw-next-days">';
		        for($i = 0; $i < 2; $i++) {
					echo '<li class="gkw-items">';
					echo '<i class="meteocons-'.str_replace('.png', '', $this->icon($this->parsedData['forecast'][$i]['icon'])).  $iconset_color . ' size-' . $this->config['forecast_icon_size']. '"></i>';
					echo '<div class="gkw-day-temp">';
					echo '<span class="gkw-day">'.$this->parsedData['forecast'][$i]['day'].'</span>';
					echo '<span class="gkw-day-day">'.$this->parsedData['forecast'][$i]['high'].'</span>';
					echo '<span class="gkw-day-night">'.$this->parsedData['forecast'][$i]['low'].'</span>';
					echo '</div>';
					echo '</li>';
		        }
			echo '</ul>';
		}
		
		echo '</div>';
	}

	/**
	 *
	 * Used in the back-end to update the module options
	 *
	 * @param array new instance of the widget settings
	 * @param array old instance of the widget settings
	 * @return updated instance of the widget settings
	 *
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['fullcity'] = strip_tags($new_instance['fullcity']);
		$instance['showCity'] = strip_tags($new_instance['showCity']);
		$instance['showHum'] = strip_tags($new_instance['showHum']);
		$instance['showWind'] = strip_tags($new_instance['showWind']);
		$instance['tempUnit'] = strip_tags($new_instance['tempUnit']);
		$instance['showPresent'] = strip_tags($new_instance['showPresent']);
		$instance['nextDays'] = strip_tags($new_instance['nextDays']);
		$instance['current_icon_size'] = strip_tags($new_instance['current_icon_size']);
		$instance['forecast_icon_size'] = strip_tags($new_instance['forecast_icon_size']);
		$instance['WOEID'] = strip_tags($new_instance['WOEID']);
		$instance['t_offset'] = strip_tags($new_instance['t_offset']);
		$instance['iconset'] = strip_tags($new_instance['iconset']);

		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_gk_weather'])) {
			delete_option( 'widget_gk_weather' );
		}

		return $instance;
	}

	/**
	 *
	 * Refreshes the widget cache data
	 *
	 * @return void
	 *
	 **/

	function refresh_cache() {
		delete_transient(md5($this->id . '_content'));
		wp_cache_delete( 'widget_gk_weather', 'widget' );
	}

	/**
	 *
	 * Outputs the HTML code of the widget in the back-end
	 *
	 * @param array instance of the widget settings
	 * @return void - HTML output
	 *
	 **/
	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$fullcity = isset($instance['fullcity']) ? esc_attr($instance['fullcity']) : 'Warsaw';
		$WOEID = isset($instance['WOEID']) ? esc_attr($instance['WOEID']) : '523920';
		$t_offset = isset($instance['t_offset']) ? esc_attr($instance['t_offset']) : '0';
		
		$showCity = isset($instance['showCity']) ? esc_attr($instance['showCity']) : 'on';
		$showHum = isset($instance['showHum']) ? esc_attr($instance['showHum']) : 'on';
		$showWind = isset($instance['showWind']) ? esc_attr($instance['showWind']) : 'on';
		$tempUnit = isset($instance['tempUnit']) ? esc_attr($instance['tempUnit']) : 'c';
		$showPresent = isset($instance['showPresent']) ? esc_attr($instance['showPresent']) : 'on';
		$nextDays = isset($instance['nextDays']) ? esc_attr($instance['nextDays']) : 'on';
		
		$iconset = isset($instance['iconset']) ? esc_attr($instance['iconset']) : 'meteocons_light';
		$current_icon_size = isset($instance['current_icon_size']) ? esc_attr($instance['current_icon_size']) : '64';
		$forecast_icon_size = isset($instance['forecast_icon_size']) ? esc_attr($instance['forecast_icon_size']) : '32';
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fullcity' ) ); ?>"><?php _e( 'City name:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fullcity' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fullcity' ) ); ?>" type="text" value="<?php echo esc_attr( $fullcity ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'WOEID' ) ); ?>"><?php _e( 'WOEID:', GKTPLNAME ); ?> (<a href="http://isithackday.com/geoplanet-explorer/"><?php _e('Get the WOEID for your city', GKTPLNAME); ?></a>)</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'WOEID' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'WOEID' ) ); ?>" type="text" value="<?php echo esc_attr( $WOEID ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 't_offset' ) ); ?>"><?php _e( 'Time offset:', GKTPLNAME ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('t_offset')); ?>" name="<?php echo esc_attr( $this->get_field_name('t_offset')); ?>">
				<option value="-12"<?php echo (esc_attr($t_offset) == '-12') ? ' selected="selected"' : ''; ?>>-12</option>
				<option value="-11"<?php echo (esc_attr($t_offset) == '-11') ? ' selected="selected"' : ''; ?>>-11</option>
				<option value="-10"<?php echo (esc_attr($t_offset) == '-10') ? ' selected="selected"' : ''; ?>>-10</option>
				<option value="-9"<?php echo (esc_attr($t_offset) == '-9') ? ' selected="selected"' : ''; ?>>-9</option>
				<option value="-8"<?php echo (esc_attr($t_offset) == '-8') ? ' selected="selected"' : ''; ?>>-8</option>
				<option value="-7"<?php echo (esc_attr($t_offset) == '-7') ? ' selected="selected"' : ''; ?>>-7</option>
				<option value="-6"<?php echo (esc_attr($t_offset) == '-6') ? ' selected="selected"' : ''; ?>>-6</option>
				<option value="-5"<?php echo (esc_attr($t_offset) == '-5') ? ' selected="selected"' : ''; ?>>-5</option>
				<option value="-4"<?php echo (esc_attr($t_offset) == '-4') ? ' selected="selected"' : ''; ?>>-4</option>
				<option value="-3"<?php echo (esc_attr($t_offset) == '-3') ? ' selected="selected"' : ''; ?>>-3</option>
				<option value="-2"<?php echo (esc_attr($t_offset) == '-2') ? ' selected="selected"' : ''; ?>>-2</option>
				<option value="-1"<?php echo (esc_attr($t_offset) == '-1') ? ' selected="selected"' : ''; ?>>-1</option>
				<option value="0"<?php echo (esc_attr($t_offset) == '0') ? ' selected="selected"' : ''; ?>>0</option>
				<option value="1"<?php echo (esc_attr($t_offset) == '1') ? ' selected="selected"' : ''; ?>>+1</option>
				<option value="2"<?php echo (esc_attr($t_offset) == '2') ? ' selected="selected"' : ''; ?>>+2</option>
				<option value="3"<?php echo (esc_attr($t_offset) == '3') ? ' selected="selected"' : ''; ?>>+3</option>
				<option value="4"<?php echo (esc_attr($t_offset) == '4') ? ' selected="selected"' : ''; ?>>+4</option>
				<option value="5"<?php echo (esc_attr($t_offset) == '5') ? ' selected="selected"' : ''; ?>>+5</option>
				<option value="6"<?php echo (esc_attr($t_offset) == '6') ? ' selected="selected"' : ''; ?>>+6</option>
				<option value="7"<?php echo (esc_attr($t_offset) == '7') ? ' selected="selected"' : ''; ?>>+7</option>
				<option value="8"<?php echo (esc_attr($t_offset) == '8') ? ' selected="selected"' : ''; ?>>+8</option>
				<option value="9"<?php echo (esc_attr($t_offset) == '9') ? ' selected="selected"' : ''; ?>>+9</option>
				<option value="10"<?php echo (esc_attr($t_offset) == '10') ? ' selected="selected"' : ''; ?>>+10</option>
				<option value="11"<?php echo (esc_attr($t_offset) == '11') ? ' selected="selected"' : ''; ?>>+11</option>
				<option value="12"<?php echo (esc_attr($t_offset) == '12') ? ' selected="selected"' : ''; ?>>+12</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'showCity' ) ); ?>"><?php _e('Show city name:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('showCity')); ?>" name="<?php echo esc_attr( $this->get_field_name('showCity')); ?>">
				<option value="on"<?php echo (esc_attr($showCity) == 'on') ? ' selected="selected"' : ''; ?>>
					<?php _e('On', GKTPLNAME); ?>
				</option>
				<option value="off"<?php echo (esc_attr($showCity) == 'off') ? ' selected="selected"' : ''; ?>>
					<?php _e('Off', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'showHum' ) ); ?>"><?php _e('Show humidity:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('showHum')); ?>" name="<?php echo esc_attr( $this->get_field_name('showHum')); ?>">
				<option value="on"<?php echo (esc_attr($showHum) == 'on') ? ' selected="selected"' : ''; ?>>
					<?php _e('On', GKTPLNAME); ?>
				</option>
				<option value="off"<?php echo (esc_attr($showHum) == 'off') ? ' selected="selected"' : ''; ?>>
					<?php _e('Off', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'showWind' ) ); ?>"><?php _e('Show wind:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('showWind')); ?>" name="<?php echo esc_attr( $this->get_field_name('showWind')); ?>">
				<option value="on"<?php echo (esc_attr($showWind) == 'on') ? ' selected="selected"' : ''; ?>>
					<?php _e('On', GKTPLNAME); ?>
				</option>
				<option value="off"<?php echo (esc_attr($showWind) == 'off') ? ' selected="selected"' : ''; ?>>
					<?php _e('Off', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tempUnit' ) ); ?>"><?php _e('Temperature unit:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('tempUnit')); ?>" name="<?php echo esc_attr( $this->get_field_name('tempUnit')); ?>">
				<option value="c"<?php echo (esc_attr($tempUnit) == 'c') ? ' selected="selected"' : ''; ?>>
					<?php _e('Celsius', GKTPLNAME); ?>
				</option>
				<option value="f"<?php echo (esc_attr($tempUnit) == 'f') ? ' selected="selected"' : ''; ?>>
					<?php _e('Fahrenheit', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'showPresent' ) ); ?>"><?php _e('Show current conditions:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('showPresent')); ?>" name="<?php echo esc_attr( $this->get_field_name('showPresent')); ?>">
				<option value="on"<?php echo (esc_attr($showPresent) == 'on') ? ' selected="selected"' : ''; ?>>
					<?php _e('On', GKTPLNAME); ?>
				</option>
				<option value="off"<?php echo (esc_attr($showPresent) == 'off') ? ' selected="selected"' : ''; ?>>
					<?php _e('Off', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nextDays' ) ); ?>"><?php _e('Show forecast:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('nextDays')); ?>" name="<?php echo esc_attr( $this->get_field_name('nextDays')); ?>">
				<option value="on"<?php echo (esc_attr($nextDays) == 'on') ? ' selected="selected"' : ''; ?>>
					<?php _e('On', GKTPLNAME); ?>
				</option>
				<option value="off"<?php echo (esc_attr($nextDays) == 'off') ? ' selected="selected"' : ''; ?>>
					<?php _e('Off', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'iconset' ) ); ?>"><?php _e('Iconset:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('iconset')); ?>" name="<?php echo esc_attr( $this->get_field_name('iconset')); ?>">
				<option value="meteocons_dark"<?php echo (esc_attr($iconset) == 'meteocons_dark') ? ' selected="selected"' : ''; ?>>
					<?php _e('Dark', GKTPLNAME); ?>
				</option>
				<option value="meteocons_light"<?php echo (esc_attr($iconset) == 'meteocons_light') ? ' selected="selected"' : ''; ?>>
					<?php _e('Light', GKTPLNAME); ?>
				</option>
			</select>
		</p>	
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'current_icon_size' ) ); ?>"><?php _e('Icon size (current weather):', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('current_icon_size')); ?>" name="<?php echo esc_attr( $this->get_field_name('current_icon_size')); ?>">
				<option value="128"<?php echo (esc_attr($current_icon_size) == '128') ? ' selected="selected"' : ''; ?>>
					<?php _e('128&times;128px', GKTPLNAME); ?>
				</option>
				<option value="64"<?php echo (esc_attr($current_icon_size) == '64') ? ' selected="selected"' : ''; ?>>
					<?php _e('64&times;64px', GKTPLNAME); ?>
				</option>
				<option value="32"<?php echo (esc_attr($current_icon_size) == '32') ? ' selected="selected"' : ''; ?>>
					<?php _e('32&times;32px', GKTPLNAME); ?>
				</option>
			</select>
		</p>	
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'forecast_icon_size' ) ); ?>"><?php _e('Icon size (forecast):', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('forecast_icon_size')); ?>" name="<?php echo esc_attr( $this->get_field_name('forecast_icon_size')); ?>">
				<option value="128"<?php echo (esc_attr($forecast_icon_size) == '128') ? ' selected="selected"' : ''; ?>>
					<?php _e('128&times;128px', GKTPLNAME); ?>
				</option>
				<option value="64"<?php echo (esc_attr($forecast_icon_size) == '64') ? ' selected="selected"' : ''; ?>>
					<?php _e('64&times;64px', GKTPLNAME); ?>
				</option>
				<option value="32"<?php echo (esc_attr($forecast_icon_size) == '32') ? ' selected="selected"' : ''; ?>>
					<?php _e('32&times;32px', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p><small>Thanks to <a href="http://dribbble.com/bluxart">Alessio Atzeni</a> kindness, this widget uses icons set called <em>Meteocons</em>.</small></p>		
	
	<?php
	}
}

// EOF