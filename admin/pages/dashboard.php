<?php
	backend::addSubnavi(lang::get('overview'), url::backend('dashboard'), 'eye');
	backend::addSubnavi(lang::get('logs'), url::backend('dashboard', ['subpage'=>'logs']), 'calendar');
  
 if(type::get('checkversion', 'int', 0) == 1) {
	$cacheFile = cache::getFileName(0, 'dynaoVersion');
	cache::exist($cacheFile, 0);
	echo message::success(lang::get('connection_again'), true); 
 }
  
  
  $versionCheck = dyn::checkVersion();
  
    if($versionCheck == lang::get('version_fail_connect')) {
		
        $message = lang::get('version_fail_connect');
        $message .= '<br /><a href="'.url::backend('dashboard', ['page'=>'dashboard', 'checkversion'=>1]).'">'.lang::get('try_again').'</a>';
        echo message::danger($message, true);  
		
	} elseif($versionCheck !== true) {
        echo message::danger($versionCheck, true);
    }
	
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