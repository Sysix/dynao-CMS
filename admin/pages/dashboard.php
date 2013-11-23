<?php
	backend::addSubnavi('Overview',	url::backend('dashboard'),	'eye');
	backend::addSubnavi('Logs',	url::backend('dashboard', ['subpage'=>'logs']),	'calendar');
	
	layout::addJs('https://www.google.com/jsapi');
	layout::addJSCode("google.load('visualization', '1', {packages:['corechart']});
	google.setOnLoadCallback(drawChart);
	
	function drawChart() {
	var data = google.visualization.arrayToDataTable([
	  ['Months', 'All', 'SEO', 'Direct link'],
	  ['Jan',  950, 350, 00],
	  ['Feb',  800, 400, 400],
	  ['Mar',  640, 330, 310],
	  ['Apr',  750, 370, 380],
	  ['Mai',  700, 300, 400],
	  ['Jun',  650, 270, 380],
	  ['Jul',  700, 290, 410],
	  ['Aug',  300, 170, 130],
	  ['Sep',  0, 0, 0],
	  ['Oct',  0, 0, 0],
	  ['Nov',  0, 0, 0],
	  ['Dec',  0, 0, 0],
	]);
	
	function resize() {		
		
		var v_width = $('#visitchart').width();
		
		var options = {
		title: '',
		chartArea: {
			top: 30,
			width: v_width-100
		},	
		legend: 'bottom',
		};
		
		var chart = new google.visualization.LineChart(document.getElementById('visitchart'));
		chart.draw(data, options);
	
	}
	
	window.onload = resize();
   	window.onresize = resize;
  }", false);
  
 if(type::get('checkversion', 'int', 0) == 1) {
	$cacheFile = cache::getFileName(0, 'dynaoVersion');
	cache::exist($cacheFile, 0);
	echo message::success('Verbindung wird erneut hergestellt', true); 
 }
  
  
  $versionCheck = dyn::checkVersion();
  
    if($versionCheck == lang::get('version_fail_connect')) {
        $message = lang::get('version_fail_connect');
        $message .= '<br /><a href="'.url::backend('dashboard', ['page'=>'dashboard', 'checkversion'=>1]).'">'.lang::get('try_again').'</a>';
        echo message::danger($message, true);  
	} elseif($versionCheck !== true) {
        echo message::danger($versionCheck, true);
    }
	
	extension::add('DASHBOARD_OVERVIEW', function($text) {
		return $text.message::info('Extension erfolgreich geladen');
	});
	
	echo extension::get('DASHBOARD_OVERVIEW', '');
?>		

            <div class="col-lg-6">
            	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title pull-left">dynao CMS News</h3>
                        <div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-default">Visit website</button>
						</div>
						<div class="clearfix"></div>
					</div>
                    <div class="panel-body">
                    	<ul class="news">
                        	<li>
                            	<h4 class="pull-left">dynao CMS v1.0 released</h4>
                                
                                <a class="btn btn-xs btn-default pull-right">read more</a>
								<div class="clearfix"></div>
                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
                            </li>
                            <li>
                            	<h4 class="pull-left">dynao CMS open beta starts</h4>
                                
                                <a class="btn btn-xs btn-default pull-right">read more</a>
								<div class="clearfix"></div>
                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
                            </li>
                            <li>
                            	<h4 class="pull-left">dynao CMS closed alpha starts</h4>
                                
                                <a class="btn btn-xs btn-default pull-right">read more</a>
								<div class="clearfix"></div>
                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
                            </li>
                        </ul>
                    </div>
				</div>
            </div>
        </div>
		
		
		<div class="clearfix"></div>