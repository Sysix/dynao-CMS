<?php
  
	if(type::get('checkversion', 'int', 0) == 1) {
		$cacheFile = cache::getFileName(0, 'dynaoVersion');
		cache::exist($cacheFile, 0);
		echo message::success(lang::get('connection_again'), true); 
	}
  	
	if(ajax::is()) {
		
		if(type::super('text')) {
			
			$mail = mail('info@dynao.de', 'Neue Idee', type::super('text'), 'From: '.dyn::get('user')->get('firstname').' '.dyn::get('user')->get('name').'<'.dyn::get('user')->get('email').'>');
			
			if($mail)
				ajax::addReturn(message::success(lang::get('idea_send')));
			else
				ajax::addReturn(message::danger(lang::get('idea_error')));
		
		} else
			ajax::addReturn(message::danger(lang::get('idea_empty')));
			
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
	
	$sql = sql::factory();
	$numPages = $sql->num('SELECT * FROM '.sql::table('structure'));
	
	$sql = sql::factory();
	$numModule = $sql->num('SELECT * FROM '.sql::table('module'));
	
	$sql = sql::factory();
	$numBlocks = $sql->num('SELECT * FROM '.sql::table('blocks'));
	
	$sql = sql::factory();
	$numAddons = $sql->num('SELECT * FROM '.sql::table('addons'));
	
	$stats[] = ['num'=>$numPages, 'text'=>lang::get('numpages'), 'btn'=>['text'=>lang::get('page_add'), 'url'=>url::backend('structure', ['subpage'=>'pages', 'action'=>'add'])]];
	$stats[] = ['num'=>$numAddons, 'text'=>lang::get('numaddons'), 'btn'=>''];
	$stats[] = ['num'=>$numModule, 'text'=>lang::get('nummodule'), 'btn'=>['text'=>lang::get('module_add'), 'url'=>url::backend('structure', ['subpage'=>'module', 'action'=>'add'])]];
	$stats[] = ['num'=>$numBlocks, 'text'=>lang::get('numblocks'), 'btn'=>['text'=>lang::get('block_add'), 'url'=>url::backend('structure', ['subpage'=>'blocks', 'action'=>'add'])]];
	
	$stats = extension::get('DASHBOARD_STATS', $stats);

?>
<section id="slide">
	<div class="row">
    
    	<?php
			

			foreach($stats as $stat) {
				
				$link = ($stat['btn']) ? ' <a class="btn btn-warning btn-xs" href="'.$stat['btn']['url'].'"><i class="fa fa-plus"></i> '.$stat['btn']['text'].'</a>' : '';
				
				echo '
					<div class="col-sm-4 col-md-2">
                	
						<div class="stat">
							<span>'.$stat['num'].$link.'</span>
							'.$stat['text'].'
						</div>
						
					</div>
				';

			}
			
		?>
        
    </div>
    <div class="row display">

    <?php

    $button = '<a http://dynao.de" target="_blank" class="btn btn-sm btn-default">'.lang::get('visit_site').'</a>';

    echo bootstrap::panel('dynao CMS', [$button], '<ul class="news">'.dyn::getNews().'</ul>', ['col'=>'lg-6']);

    echo bootstrap::panel(lang::get('your_idea'), [], '
    <div class="row">
        <div class="col-lg-12">
            '.lang::get('idea_text').'
            <hr />
        </div>
        <div class="col-md-9">
           <textarea class="form-control"></textarea>
        </div>
        <div class="col-md-3">
           <p><button class="btn btn-default">'.lang::get('send').'</button></p>
            <div id="ajax-content"></div>
        </div>
    </div>
    ', ['id'=>'idea', 'col'=>'lg-6']);
    ?>
    </div>
    <div class="expand">
    	<i class="fa fa-chevron-up"></i>
    </div>
</section>
		
<div class="row">
    <?php

    $button = '<a href="http://dynao.de" target="_blank" class="btn btn-sm btn-default">'.lang::get('all_addons').'</a>';

    echo bootstrap::panel(lang::get('addons'), [$button], dyn::getAddons(), ['table' => true, 'col'=>'lg-6']);

    $button = '<a href="http://dynao.de" target="_blank" class="btn btn-sm btn-default">'.lang::get('all_modules').'</a>';

    echo bootstrap::panel(lang::get('modules'), [$button], dyn::getModules(), ['table' => true, 'col'=>'lg-6']);

    ?>
</div>

<?php echo extension::get('DASHBOARD_OVERVIEW', ''); ?>