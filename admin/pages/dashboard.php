<?php
	backend::addSubnavi('Overview',	url::backend('structure'),	'eye-open');
	backend::addSubnavi('Statistics',	url::backend('structure'),	'bar-chart');
	backend::addSubnavi('Logs',	url::backend('structure'),	'calendar');
?>		

<?php echo message::info('<strong>Warning!</strong> Please check your Version of dynao CMS', true); ?>
		<div class="row">		
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title pull-left">Visitor statistics</h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-default">&laquo;</button>
							<button type="button" class="btn btn-sm btn-default">2013</button>
							<button type="button" class="btn btn-sm btn-default">&raquo;</button>
						</div>
						<div class="clearfix"></div>
					</div>
					<div id="visitchart" style="height:280px;"></div>
				</div>
			</div>
			
			<div class="clearfix"></div>
			
		</div>
        
        <div class="row">
        	<div class="col-lg-6">
            	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title pull-left">Newest User</h3>
                        <div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-default">View all</button>
						</div>
						<div class="clearfix"></div>
					</div>
                    <div class="content">
                    	<table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr>
                                    <td>5</td>
                                    <td>Test</td>
                                    <td>08-11-2013</td>
                                    <td class="options">
                                    	<span class="btn-group">
                                            <a title="Move up" href="" class="icon-caret-up btn btn-xs btn-default"></a> 
                                            <a title="Move down" href="" class="icon-caret-down btn btn-xs btn-default"></a> 
                                            <a title="Edit" href="" class="icon-pencil btn btn-xs btn-default"></a> 
                                            <a title="Delete" href="" class="icon-trash btn btn-xs btn-danger"></a>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Döner</td>
                                    <td>08-04-2013</td>
                                    <td class="options">
                                    	<span class="btn-group">
                                            <a title="Move up" href="" class="icon-caret-up btn btn-xs btn-default"></a> 
                                            <a title="Move down" href="" class="icon-caret-down btn btn-xs btn-default"></a> 
                                            <a title="Edit" href="" class="icon-pencil btn btn-xs btn-default"></a> 
                                            <a title="Delete" href="" class="icon-trash btn btn-xs btn-danger"></a>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Fresh Dumbledore</td>
                                    <td>08-04-2013</td>
                                    <td class="options">
                                    	<span class="btn-group">
                                            <a title="Move up" href="" class="icon-caret-up btn btn-xs btn-default"></a> 
                                            <a title="Move down" href="" class="icon-caret-down btn btn-xs btn-default"></a> 
                                            <a title="Edit" href="" class="icon-pencil btn btn-xs btn-default"></a> 
                                            <a title="Delete" href="" class="icon-trash btn btn-xs btn-danger"></a>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Aaron</td>
                                    <td>08-02-2013</td>
                                    <td class="options">
                                    	<span class="btn-group">
                                            <a title="Move up" href="" class="icon-caret-up btn btn-xs btn-default"></a> 
                                            <a title="Move down" href="" class="icon-caret-down btn btn-xs btn-default"></a> 
                                            <a title="Edit" href="" class="icon-pencil btn btn-xs btn-default"></a> 
                                            <a title="Delete" href="" class="icon-trash btn btn-xs btn-danger"></a>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Alexander</td>
                                    <td>08-01-2013</td>
                                    <td class="options">
                                    	<span class="btn-group">
                                            <a title="Move up" href="" class="icon-caret-up btn btn-xs btn-default"></a> 
                                            <a title="Move down" href="" class="icon-caret-down btn btn-xs btn-default"></a> 
                                            <a title="Edit" href="" class="icon-pencil btn btn-xs btn-default"></a> 
                                            <a title="Delete" href="" class="icon-trash btn btn-xs btn-danger"></a>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
      					</table>
                    </div>
				</div>
            </div>
            
            <div class="col-lg-6">
            	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title pull-left">dynao CMS News</h3>
                        <div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-default">Visit website</button>
						</div>
						<div class="clearfix"></div>
					</div>
                    <div class="content">
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