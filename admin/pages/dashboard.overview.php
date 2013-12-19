<?php
  
 if(type::get('checkversion', 'int', 0) == 1) {
	$cacheFile = cache::getFileName(0, 'dynaoVersion');
	cache::exist($cacheFile, 0);
	echo message::success(lang::get('connection_again'), true); 
 }
  
  
  $versionCheck = dyn::checkVersion();
  
    if($versionCheck == lang::get('version_fail_connect')) {
		
        $message = lang::get('version_fail_connect');
        $message .= '<br /><a href="'.url::backend('dashboard', ['subpage'=>'overview', 'checkversion'=>1]).'">'.lang::get('try_again').'</a>';
        echo message::danger($message, true);  
		
	} elseif($versionCheck !== true) {
        echo message::danger($versionCheck, true);
    }
	
	echo extension::get('DASHBOARD_OVERVIEW', '');
?>		
<div class="row">

	<div class="col-lg-6">
        <div class="panel panel-default">
        	<div class="panel-heading">
        		<h3 class="panel-title pull-left"><?php echo lang::get('addons'); ?></h3>
        		<div class="btn-group pull-right">
        			<a href="" target="_blank" class="btn btn-sm btn-default"><?php echo lang::get('all_addons'); ?></a>
        		</div>
        		<div class="clearfix"></div>
        	</div>
            <div class="table-responsive">
        	<?php echo dyn::getAddons(); ?>  
            </div>
        </div>
    </div>

	<div class="col-lg-6">
        <div class="panel panel-default">
        	<div class="panel-heading">
        		<h3 class="panel-title pull-left">dynaoCMS</h3>
        		<div class="btn-group pull-right">
        			<a href="" target="_blank" class="btn btn-sm btn-default"><?php echo lang::get('visit_site'); ?></a>
        		</div>
        		<div class="clearfix"></div>
        	</div>
        	<div class="panel-body">
        		<ul class="news">
                	<?php echo dyn::getNews(); ?>        			
        		</ul>
        	</div>
        </div>
    </div>

</div>