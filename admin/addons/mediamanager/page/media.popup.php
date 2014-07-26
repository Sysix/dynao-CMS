<?php

backend::$subnavi = [];

$catId = type::super('catId', 'int', 0);

if (!$catId) {
    $catId = type::session('media_cat', 'int', $catId);
}

if (!$catId) {
    $sql = sql::factory();
    $sql->query('SELECT id FROM ' . sql::table('media_cat') . ' ORDER BY id LIMIT 1')->result();
    $catId = $sql->get('id');
}

type::addSession('media_cat', $catId);


$table = table::factory(['class' => ['media-table']]);

$table->setSql('SELECT * FROM ' . sql::table('media') . ' WHERE `category` = ' . $catId);

$table->addRow()
    ->addCell()
    ->addCell(lang::get('title'))
    ->addCell(lang::get('file_type'))
    ->addCell(lang::get('action'));

$table->addCollsLayout('50,*,100,250');

$table->addSection('tbody');

if ($table->numSql()) {

    while ($table->isNext()) {

        $media = new media($table->getSql());

        $select = '<button data-id="' . $table->get('id') . '" data-name="' . $table->get('filename') . '" data-loading-text="' . lang::get('selected') . '" class="btn btn-sm btn-warning dyn-media-select">' . lang::get('select') . '</button>';

        $table->addRow()
            ->addCell($media->getIcon())
            ->addCell($media->get('title'))
            ->addCell($media->getExtension())
            ->addCell('<span class="btn-group">' . $select . '</span>');

        $table->next();

    }

} else {

    $table->addRow()
        ->addCell(lang::get('no_entries'), ['colspan' => 4]);

}

?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo lang::get('media'); ?></h3>
                    <?php
                    if (dyn::get('user')->hasPerm('media[edit]')) {
                        ?>
                        <div class="btn-group pull-right">
                            <a href="<?php echo url::backend('media', ['subpage' => 'files', 'action' => 'add', 'id' => $id]); ?>"
                               class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <select class="form-control" id="media-select-category-ajax" name="catId">
                        <option value="0"><?php echo lang::get('no_category'); ?></option>
                        <?php echo mediaUtils::getTreeStructure(0, 0, ' &nbsp;', $catId); ?>
                    </select>

                    <div id="load">
                        <?php echo $table->show(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

if (!type::super('layout', 'string', false)) {
    exit();
}

?>