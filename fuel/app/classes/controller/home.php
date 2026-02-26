<?php

class Controller_Home extends Controller_Base
{
    public function action_index()
    {
        // セッションに入れたユーザー名を表示に使う
        $data = array(
            'username' => \Session::get('username'),
        );

        return \Response::forge(\View::forge('home/index', $data));
    }
}