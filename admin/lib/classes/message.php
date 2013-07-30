<?php

class message {

    static public function warning($message, $close = false) {
    
        $text = '<div class="alert alert-warning fade in">';
        
        if($close == true) {
        	$text .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        }
        
        $text .= $message;
        $text .= '</div>';
        
        return $text;
    }
    
    static public function info($message, $close = false) {
    
        $text = '<div class="alert alert-info fade in">';
        
        if($close == true) {
        	$text .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        }
        
        $text .= $message;
        $text .= '</div>';
        
        return $text;
    }
    
    static public function danger($message, $close = false) {
    
        $text = '<div class="alert alert-danger fade in">';
        
        if($close == true) {
        	$text .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        }
        
        $text .= $message;
        $text .= '</div>';
        
        return $text;
    }
    
    static public function success($message, $close = false) {
    
        $text = '<div class="alert alert-success fade in">';
        
        if($close == true) {
        	$text .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        }
        
        $text .= $message;
        $text .= '</div>';
        
        return $text;
    }

}

?>
