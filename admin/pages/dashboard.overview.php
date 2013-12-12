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
        	<?php
				$addons = table::factory(['class'=> ['table', 'table-spriped', 'table-hover']]);
				
				$addons->addCollsLayout('60, 140, *, 110');
				
				$addons->addRow()
				->addCell(lang::get('vote'))
				->addCell(lang::get('name'))
				->addCell(lang::get('description'))
				->addCell();
				
				$addons->addSection('tbody');
				
				$addons->addRow()
				->addCell('<span class="label label-danger">15%</span>')
				->addCell('TinyMCE')
				->addCell('Textares mit dem TinyMCE versehen')
				->addCell('<a href="" class="btn btn-sm btn-default">'.lang::get('download').'</a>');
				
				$addons->addRow()
				->addCell('<span class="label label-success">90%</span>')
				->addCell('Mediamanager')
				->addCell('Median verwalten')
				->addCell('<a href="" class="btn btn-sm btn-default">'.lang::get('download').'</a>');
				
				$addons->addRow()
				->addCell('<span class="label label-warning">55%</span>')
				->addCell('Wiki')
				->addCell('Artikel und Kategorien einfach verwalten')
				->addCell('<a href="" class="btn btn-sm btn-default">'.lang::get('download').'</a>');
				
				$addons->addRow()
				->addCell('<span class="label label-success">80%</span>')
				->addCell("Meta Info's")
				->addCell('')
				->addCell('<a href="" class="btn btn-sm btn-default">'.lang::get('download').'</a>');
				
				$addons->addRow()
				->addCell('<span class="label label-warning">60%</span>')
				->addCell('Community')
				->addCell('Frontend Login')
				->addCell('<a href="" class="btn btn-sm btn-default">'.lang::get('download').'</a>');
				
				echo $addons->show();
				
			?>
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
        			<li>
        				<h4 class="pull-left">dynao CMS v1.0 released</h4>
        
        				<a class="btn btn-xs btn-default pull-right"><?php echo lang::get('read_more'); ?></a>
        				<div class="clearfix"></div>
        				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
        			</li>
                    <li>
        				<h4 class="pull-left">dynao CMS v1.0 released</h4>
        
        				<a class="btn btn-xs btn-default pull-right"><?php echo lang::get('read_more'); ?></a>
        				<div class="clearfix"></div>
        				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
        			</li>
                    <li>
        				<h4 class="pull-left">dynao CMS v1.0 released</h4>
        
        				<a class="btn btn-xs btn-default pull-right"><?php echo lang::get('read_more'); ?></a>
        				<div class="clearfix"></div>
        				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
        			</li>
        		</ul>
        	</div>
        </div>
    </div>

</div>