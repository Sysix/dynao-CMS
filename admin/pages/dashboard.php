<?php echo message::info('<strong>Warning!</strong> Please check your Version of dynao CMS', true); ?>
		<div class="row-fluid">
		
			<div class="span12">
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title pull-left">Visitor statistics</h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-small btn-default">&laquo;</button>
							<button type="button" class="btn btn-small btn-default">Year</button>
							<button type="button" class="btn btn-small btn-default">&raquo;</button>
						</div>
						<div class="clearfix"></div>
					</div>
					<div id="visitchart" style="height:280px;"></div>
				</div>
			</div>
			
			<div class="clearfix"></div>
			
		</div>
		
		<div class="row-fluid">
		
			<div class="span4">
				<div class="box">
				
					<div class="btn-group">
						<button type="button" class="btn btn-default">Left</button>
						<button type="button" class="btn btn-default">Middle</button>
						<button type="button" class="btn btn-default">Right</button>
					</div>
					
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							Action <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul>
					</div>
					
					<div class="accordion" id="accordion2">
					
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
									Collapsible Group Item #1
								</a>
							</div>
							<div id="collapseOne" class="accordion-body collapse in">
								<div class="accordion-inner">
									...
								</div>
							</div>
						</div>
						
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
									Collapsible Group Item #2
								</a>
							</div>
							<div id="collapseTwo" class="accordion-body collapse">
								<div class="accordion-inner">
									...
								</div>
							</div>
						</div>
						
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
									Collapsible Group Item #3
								</a>
							</div>
							<div id="collapseThree" class="accordion-body collapse">
								<div class="accordion-inner">
									...
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
			
			<div class="span4">
				<div class="box">
				
					<ul class="pagination">
						<li class="disabled"><a href="#">&laquo;</a></li>
						<li><a href="#">&laquo;</a></li>
						<li><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#">5</a></li>
						<li><a href="#">&raquo;</a></li>
					</ul>
					
					<div class="clear"></div>
					
					<span class="label">Default</span>
					<span class="label label-success">Success</span>
					<span class="label label-warning">Warning</span>
					<span class="label label-danger">Danger</span>
					<span class="label label-info">Info</span>
					
					<div class="progress">
				   		<div class="progress-bar progress-bar-info" style="width: 20%"></div>
					</div>
					
					<div class="progress">
						<div class="progress-bar progress-bar-success" style="width: 40%"></div>
					</div>
					
					<div class="progress">
						<div class="progress-bar progress-bar-warning" style="width: 60%"></div>
					</div>

					<div class="progress">
						<div class="progress-bar progress-bar-danger" style="width: 80%"></div>
					</div>
					
					<div class="progress progress-striped active">
						<div class="progress-bar" style="width: 45%"></div>
					</div>
					
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title">Panel title</h3>
				   		</div>
						Panel content
					</div>
					
				</div>
			</div>
			
			<div class="span4">
				<div class="box">
				
					<div class="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Warning!</strong> Best check yo self, you're not looking too good.
					</div>
					
					<div class="alert alert-danger">danger</div>
					<div class="alert alert-success">success</div>
					<div class="alert alert-info">info</div>
					
					<a data-toggle="modal" href="#myModal" class="btn btn-primary btn-medium">Launch demo modal</a>
					
					<div class="modal" id="myModal">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Modal title</h4>
								</div>
								<div class="modal-body">
									...
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary">Save changes</button>
								</div>
							</div>
						</div>
					</div>
				
				</div>
			</div>
			
			<div class="clearfix"></div>
			
		</div><!--end .row-fluid-->
		
		<div class="clearfix"></div>