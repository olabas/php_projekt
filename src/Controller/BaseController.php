<?php
/**
 * Created by PhpStorm.
 * User: ola
 * Date: 29.07.15
 * Time: 22:56
 */

namespace Controller;


class BaseController
{

    public function getView()
    {
        return array(
            'info' => NULL,
            'warning' => NULL,
            'error' => NULL
        );
    }
}