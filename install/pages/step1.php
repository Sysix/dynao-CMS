<?php
	chmod(dir::cache(), 0755);
?>
<div class="row">

    <div class="col-lg-12">
    
    <div class="panel panel-default">
    
            <div class="panel-heading">
            	<h3 class="panel-title">bla</h3>
            </div>
            <div class="panel-body">
            
            	<?php var_dump(fileperms(dir::cache())); ?>
            
            </div>
    
    </div>

</div>