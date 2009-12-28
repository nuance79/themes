<?php
//update_option('siteurl','http://www.radiotierra.org');
//update_option('home','http://www.radiotierra.org');

if ( function_exists('register_sidebars') )
    register_sidebars(2);

function getNowPlaying()
 {
	require $_SERVER['DOCUMENT_ROOT'] . '/simplepie.inc';
	putenv("TZ=America/Los_Angeles");
	$thetime = date("c");
	$endtime = date("c", mktime()+(1));
	//echo $thetime;
	$url = 'http://www.google.com/calendar/feeds/radiotierra.org_t19nv7gflk7uu9fk9fas7fh1cs%40group.calendar.google.com/public/basic';
	$url .= '?start-min=' . $thetime . '&start-max=' . $endtime;
	//$test_timestamp = 'http://www.google.com/calendar/feeds/radiotierra.org_t19nv7gflk7uu9fk9fas7fh1cs%40group.calendar.google.com/public/basic?start-min=2009-04-16T10:32:48-07:00&start-max=2009-04-16T10:32:48-07:00';
	//echo $url2; return;
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->init();
	 
	// default starting item
	$start = 0;
	 
	// default number of items to display. 0 = all
	$length = 1; 
	 
	// if single item, set start to item number and length to 1
	if(isset($_GET['item']))
	{
	        $start = $_GET['item'];
	        $length = 1;
	}
	 
	// set item link to script uri
	$link = $_SERVER['REQUEST_URI'];
	$output = "";	 
	// loop through items
	foreach($feed->get_items($start,$length) as $key=>$item)
	{
	        // set query string to item number
	        $queryString = '?item=' . $key;
	 
	        // if we're displaying a single item, set item link to itself and set query string to nothing
	        if(isset($_GET['item']))
	        {
	                $link = $item->get_link();
	                $queryString = '';        
	        }
	        // We want to grab the Google-namespaced <gd:when> tag.
		$when = $item->get_item_tags('http://schemas.google.com/g/2005', 'when');
		// Once we grab the tag, let's grab the startTime attribute
		$date = $when[0]['attribs']['']['startTime'];
		$date2 = $when[0]['attribs']['']['endTime'];
			 
		// Let's convert it all to UNIX timestamp. This will be used for sorting.
		$sortDate = SimplePie_Misc::parse_date($date);
		$sortDate2 = SimplePie_Misc::parse_date($date2);

		// Let's format it with date(). This will be the date we display.
		$gCalStart = date('g:ia', $sortDate);
		$gCalStop = date('g:ia', $sortDate2);
		
	        // display item title and date    
	        echo '<a href="http://radiotierra.org/horario-schedule" title="See Full Schedule"><strong style="font-size:1.1em;font-variant:small-caps">' .$item->get_title() . '</strong></a>';
	        //echo '&nbsp;&nbsp;<span style="font-size:0.8em">('. $gCalStart. ' &#8212; ' . $gCalStop. ')</span>';
	}
}	
?>