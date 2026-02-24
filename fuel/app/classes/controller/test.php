<?php

class Controller_Test extends Controller
{
    public function action_index()
    {
        $row = \DB::query('SELECT COUNT(*) AS cnt FROM users')->execute()->current();
        return 'DB OK! users count = ' . $row['cnt'];
    }
}