<?php

/**
 * Base controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Controller;

/**
 * Class BaseController.
 *
 * @package Controller
 */
class BaseController
{

    /**
     * Get view function
     *
     * @access public
     * @return array
     */
    public function getView()
    {
        return array(
            'info' => null,
            'warning' => null,
            'error' => null
        );
    }
}
