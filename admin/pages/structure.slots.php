<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><?php echo lang::get('slots_current_page'); ?></h3>
                <div class="btn-group pull-right">
                	<a class="btn btn-sm btn-default" href="<?php echo url::backend('structure', ['subpage'=>'slots', 'action'=>'add']); ?>"><?php echo lang::get('add'); ?></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
           		<?php var_dump(slot::getArray()); ?>
            </div>
        </div>
    </div>
</div>