<?php

class Controller_Home extends Controller_Base
{
    public function action_index()
    {
        // セッションに入れたユーザー名を表示に使う
        $data = array(
            'username' => \Session::get('username'),
            'last_login_at' => (string) \Cookie::get('last_login_at', ''),
        );

        return $this->render('home/index', $data, array('home'), 'Home');
    }
}
