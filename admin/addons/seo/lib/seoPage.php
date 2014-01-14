<?php

class seoPage {
	
	/*
	 * SEO-Formular generieren
	 *
	 * @param int $id  Struktur-Id
	 */
	public static function generateForm($id) {
		
		$form = form::factory('structure', 'id='.$id, 'index.php');
		$form->addParam('action', 'seo');
		$form->setMode('edit');
		
		if($form->get('seo_title')) {
			$title = $form->get('seo_title');
		} else {
			$title = $form->get('name');
		}
		
		$field = $form->addTextField('seo_title', $form->get('seo_title'));
		$field->fieldName(lang::get('title'));
		$field->setId('seo-title-text');
		$field->setSuffix('<p class="help-block"><span id="seo-title">'.$title.'</span> | '.dyn::get('hp_name').'</p>');
		
		$field = $form->addTextareaField('seo_keywords', $form->get('seo_keywords'));
		$field->fieldName(lang::get('seo_keywords'));
		$field->addRows(2);
		
		$field = $form->addTextareaField('seo_description', $form->get('seo_description'));
		$field->fieldName(lang::get('description'));
		$field->addRows(2);
		
		$field = $form->addCheckboxField('seo_robots', $form->get('seo_robots', 1));
		$field->fieldName(lang::get('seo_index'));
		$field->add(1, '');
		
		if($form->get('seo_costum_url')) {
			$url = $form->get('seo_costum_url');
		} else {
			$url = seo_rewrite::makeSEOName($form->get('name'));
		}
		
		$field = $form->addTextField('seo_costum_url', $form->get('seo_costum_url'));
		$field->fieldName(lang::get('seo_self_url'));
		$field->setId('seo-costum-url-text');
		$field->setSuffix('<p class="help-block">'.dyn::get('hp_url').'<span id="seo-costum-url">'.$url.'</span></p>');
		
		$form->addHiddenField('id', $id);
		
		if($form->isSubmit()) {
			
			$costum_url = $form->get('seo_costum_url');
			$costum_url = str_replace(dyn::get('hp_url'), '', $costum_url);
			$costum_url = str_replace('.html', '', $costum_url);
			
			$costum_url = trim($costum_url, '/');
			
			$costum_url = seo_rewrite::makeSEOName($costum_url);
			
			if($costum_url && $costum_url != '.html') {
				$form->addPost('seo_costum_url', $costum_url);
			} else {
				$form->addPost('seo_costum_url', '');
			}
				
		}
		
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title pull-left" id="seo-default-title"><?php echo $form->get('name'); ?></h3>
                <span class="pull-right">
                	<a href="<?php echo url::backend('structure', ['subpage'=>'pages', 'action'=>'edit', 'id'=>$id]); ?>" class="btn btn-sm btn-warning"><?php echo lang::get('edit'); ?></a>
					<a href="<?php echo url::backend('structure', ['subpage'=>'pages', 'structure_id'=>$id]); ?>" class="btn btn-sm btn-default"><?php echo lang::get('back'); ?></a>
                </span>
                <div class="clearfix"></div>
			</div>
			<div class="panel-body">
				<?php echo $form->show(); ?>
			</div>
		</div>
	</div>
</div>
<?php
		
	}
	
	/*
	 * SEO-Button einfügen
	 *
	 * @param string $output Inhalt der Seite
	 * @param int $structure_id Struktur-Id
	 * @return string
	 */
	public static function generateButton($output, $structure_id) {
		
		// Bugfix UTF-8
		$output = mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8');
			
		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		
		$xpath = new DOMXpath($dom);
		$buttons = $xpath->query(".//div[@class='pull-right']")->item(0);
		
		// Neuen Button erstellen
		$seobutton = $dom->createElement('a', lang::get('seo'));
		$seobutton->setAttribute('class', 'btn btn-sm btn-default');
		
		$url = url::backend('structure', ['subpage'=>'pages', 'action'=>'seo', 'id'=>$structure_id]);
		$seobutton->setAttribute('href', str_replace('&amp;', '&', $url));
		
		// Ihn vor den ersten Button einfügen (prependChild gibt's nicht in PHP)
		$firstButton = $buttons->getElementsByTagName('a')->item(0);
		$buttons->insertBefore($seobutton, $firstButton);		
		
		$output = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(['<html>', '</html>', '<body>', '</body>'], '', $dom->saveHTML()));
		
		return $output;
				
	}
	
}

?>