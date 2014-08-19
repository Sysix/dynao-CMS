<?php

class bootstrap
{
    protected static $panelSettings = [
        'class' => 'default',
        'col' => 'lg-12',
        'table' => false,
        'id' => ''
    ];

    /*
     * return a Bootstrap Panel structure
     * @param string $title
     * @param array $buttons List of Buttons
     * @param string content
     * @param array settings
     * @return string
     */
    public static function panel($title, array $buttons = [], $content,array $settings = [])
    {
        $settings = array_merge(self::$panelSettings, $settings);

        if(!$settings['table']) {
            $content = '<div class="panel-body">'.$content.'</div>';
        }

        $id = '';

        if($settings['id'] != '') {
            $id = ' id="'.$settings['id'].'"';
        }

        return '
        <div class="col-'.$settings['col'].'"'.$id.'>
            <div class="panel panel-'.$settings['class'].'">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left">' . $title . '</h3>
                    <div class="pull-right btn-group">
                    ' . implode('', $buttons) . '
                    </div>
                    <div class="clearfix"></div>
                </div>
                ' . $content . '
            </div>
        </div>';
    }

}

?>