<?php

namespace App\View;


/**
 * Description of Factory
 *
 * @author mbele
 */
class Factory {
    
    protected $view;

    public static function getEngine() {
        return new \Slim\Views\Twig(__DIR__ . '/../../resources/views', [
            'cache' => false
        ]);
    }
    
    public function make($view, $data = []) {
        $this->view = static::getEngine()->fetch($view, $data);
        
        return $this;
    }
    
    public function render() {
        return $this->view;
    }
}
