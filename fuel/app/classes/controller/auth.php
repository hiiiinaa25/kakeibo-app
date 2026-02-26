<?php

class Controller_Auth extends Controller
{
    public function action_register()
    {
        //新規登録
        if (\Input::method() === 'POST')
        {
            // POST送信されたときだけ処理する
            $username = trim((string)\Input::post('username'));
            $email    = trim((string)\Input::post('email'));
            $password = (string)\Input::post('password');
            $confirm  = (string)\Input::post('password_confirm');

            // 未入力チェック
            if ($username === '' || $email === '' || $password === '' || $confirm === '')
            {
                return \Response::forge('未入力があります');
            }
            // パスワード一致チェック
            if ($password !== $confirm)
            {
                return \Response::forge('パスワードが一致しません');
            }
            //　パスワード暗号化
            $hash = password_hash($password, PASSWORD_DEFAULT);

            \DB::insert('users')->set(array(
                'username' => $username,
                'email'    => $email,
                'password' => $hash,
            ))->execute();
            // 登録後ログイン画面へ移動
            return \Response::redirect('auth/login');
        }

        return \Response::forge(\View::forge('auth/register'));
    }
    //ログイン
    public function action_login()
    {
        // エラーメッセージ用
        $data = array('error' => '');

        // POST送信されたとき
        if (\Input::method() === 'POST')
        {
            // 入力されたメールとパスワードを取得
            $email = trim((string)\Input::post('email'));
            $pass  = (string)\Input::post('password');

            // 未入力チェック
            if ($email === '' || $pass === '')
            {
                $data['error'] = '未入力があります';
                return \Response::forge(\View::forge('auth/login', $data));
            }

            // DBからメールアドレス一致のユーザーを取得
            $row = \DB::select()
                ->from('users')
                ->where('email', '=', $email)
                ->execute()
                ->current();

            // ユーザーが存在しない場合
            if (!$row)
            {
                $data['error'] = 'ユーザーが存在しません';
                return \Response::forge(\View::forge('auth/login', $data));
            }
            // パスワード確認
            // 入力パスワードとDBのハッシュを比較
            if (!password_verify($pass, $row['password']))
            {
                $data['error'] = 'パスワードが違います';
                return \Response::forge(\View::forge('auth/login', $data));
            }
            // ログイン成功
            // セッションにユーザー情報を保存
            \Session::set('user_id', $row['id']);
            \Session::set('username', $row['username']);

            // トップページへ移動
            return \Response::redirect('/'); 
        }
        return \Response::forge(\View::forge('auth/login', $data));
    }
    // ログアウト
    public function action_logout()
    {
        // セッション削除
        \Session::delete('user_id');
        \Session::delete('username');

        // ログイン画面へ戻る
        return \Response::redirect('auth/login');
    }
}