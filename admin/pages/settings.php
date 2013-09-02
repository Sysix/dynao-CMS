<?php
	backend::addSubnavi('General',	url::backend(''),	'eye-open');
	backend::addSubnavi('Media',	url::backend(''),	'picture');
	backend::addSubnavi('SEO',	url::backend(''),	'group');
?>

<div class="row">
	
    <div class="col-lg-5">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title">General</h3>
            </div>
            <div class="panel-body">
            	...
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title">System</h3>
            </div>
            <table class="table table-striped table-hover">
            	<thead>
                	<th>Type</th>
                    <th>Value</th>
                </thead>
                <tbody>
                	<tr>
                    	<td>Language frontend</td>
                        <td>de_de</td>
                    </tr>
                	<tr>
                    	<td>Cache lifetime</td>
                        <td>60</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-body">
            	...
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>

</div>