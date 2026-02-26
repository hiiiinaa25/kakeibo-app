<?php

class Controller_Base extends Controller
{
    public function before()
    {
        parent::before();

        // ログインしてなければログイン画面へ
        if (!\Session::get('user_id'))
        {
            return \Response::redirect('auth/login');
        }
    }
}