<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">.htaccess Datei einrichten</h3>
			</div>
			<div class="panel-body">
				<?php
				
				$form = form::factory('user', 'id='.dyn::get('user')->get('id'), 'index.php');
				$form->setSave(false);
				$form->delButton('back');
				$form->delButton('save');
				
				$htaccessRoot = dir::base('.htaccess');
				$htaccessOrg = dir::addon('seo', '_htaccess');
				
				$field = $form->addRadioField('redirect', 'www');
				$field->fieldName('Umleiten auf');
				$field->add('www', 'www.');
				$field->add('no_www', 'Nicht wwww.');
				
				$urlPath = parse_url(dyn::get('hp_url'));
				
				$subdir = '/';
				if(isset($urlPath['path'])) {
					$subdir = trim($urlPath['path'], '/');
				}
				
				$field = $form->addTextField('rewrite_base', $subdir);
				$field->setAttribute('readonly', 'readonly');
				$field->fieldName('Rewrite Base');
				
				if($form->isSubmit()) {
					copy($htaccessOrg, $htaccessRoot);
					
					$htaccessContent = file_get_contents($htaccessRoot);
					
					if($form->get('redirect') == 'www') {
						$rewriteCond = '
						RewriteCond %{HTTP_HOST} ^[^.]+\.[^.]+$
						RewriteCond %{REQUEST_URI} !/admin
						RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [L,R=301]';	
					} else {
						$rewriteCond = '
						RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
						RewriteCond %{REQUEST_URI} !/admin
						RewriteRule ^(.*)$ http://%1/$1 [R=301,L]
						';
					}
						
					$htaccessContent = str_replace('{www_rewriteCond}', $rewriteCond, $htaccessContent);
					$htaccessContent = str_replace('{rewrite_base}', $form->get('rewrite_base'));
					
					if(!file_put_contents($htaccessRoot, $htaccessContent)) {
						$form->setErrorMessage('.htaccess Datei konnte nicht geändert werden');	
					}
					
				}
				
				$form->show();
				
				?>				
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Template ergänzen</h3>
			</div>
			<div class="panel-body">
				<pre>
&lt;head&gt;
	<?php echo htmlspecialchars(seo::getHTML()); ?>
&lt;/head&gt;
				</pre>
			</div>
		</div>
	</div>
	
</div>