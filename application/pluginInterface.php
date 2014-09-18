<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author computer
 */
interface pluginInterface {
    public function _init($name, $desc, $vars); // MUST set these variables so that the plugin can inteface with the management page.
    
}
