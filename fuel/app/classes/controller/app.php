<?php

class Controller_App extends Controller
{
    protected function render($view, array $data = array(), array $styles = array(), $title = 'Kakeibo App')
    {
        if ( ! in_array('common', $styles, true))
        {
            array_unshift($styles, 'common');
        }

        return \Response::forge(\View::forge('template', array(
            'title' => $title,
            'styles' => $styles,
            'content' => \View::forge($view, $data),
        )));
    }
}
