<?php
  
	if(type::get('checkversion', 'int', 0) == 1) {
		$cacheFile = cache::getFileName(0, 'dynaoVersion');
		cache::exist($cacheFile, 0);
		echo message::success(lang::get('connection_again'), true); 
	}
  
  
	$versionCheck = dyn::checkDynVersion();
	
    if($versionCheck === lang::get('version_fail_connect')) {
		
        $message = lang::get('version_fail_connect');
        $message .= '<br /><a href="'.url::backend('dashboard', ['subpage'=>'overview', 'checkversion'=>1]).'">'.lang::get('try_again').'</a>';
        echo message::danger($message, true);  
		
	} elseif($versionCheck) {
        echo message::danger($versionCheck, true);
    }
	
	$stats = [];
	
	$stats[] = ['num'=>12, 'text'=>'Text here'];
	$stats[] = ['num'=>12, 'text'=>'Text here'];
	$stats[] = ['num'=>12, 'text'=>'Text here'];
	$stats[] = ['num'=>12, 'text'=>'Text here'];
	$stats[] = ['num'=>12, 'text'=>'Text here'];
	$stats[] = ['num'=>12, 'text'=>'Text here'];
	
	$stats = extension::get('DASHBOARD_STATS', $stats);

?>
<section id="slide">
	<div class="row">
    
    	<?php
			
			$i = 1;
			foreach($stats as $stat) {
				
				echo '
					<div class="col-sm-4 col-md-2">
                	
						<div class="stat">
							<span>'.$stat['num'].'</span>
							'.$stat['text'].'
						</div>
						
					</div>
				';
				
				$i++;
			}
			
		?>
        
    </div>
    <div class="row">
    
    	<div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left">dynaoCMS</h3>
                    <div class="btn-group pull-right">
                        <a href="http://dynao.de" target="_blank" class="btn btn-sm btn-default"><?php echo lang::get('visit_site'); ?></a>
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
    <div class="expand">
    	<i class="fa fa-chevron-up"></i>
    </div>
</section>
		
<div class="row">
    
	<div class="col-lg-6">
        <div class="panel panel-default">
        	<div class="panel-heading">
        		<h3 class="panel-title pull-left"><?php echo lang::get('addons'); ?></h3>
        		<div class="btn-group pull-right">
        			<a href="http://dynao.de" target="_blank" class="btn btn-sm btn-default"><?php echo lang::get('all_addons'); ?></a>
        		</div>
        		<div class="clearfix"></div>
        	</div>
            <div class="table-responsive">
        	<?php echo dyn::getAddons(); ?>  
            </div>
        </div>
    </div>

</div>

<?php echo extension::get('DASHBOARD_OVERVIEW', ''); ?>