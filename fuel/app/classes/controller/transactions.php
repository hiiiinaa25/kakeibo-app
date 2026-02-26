<?php

class Controller_Transactions extends Controller_Base
{
    // 収支データ登録（表示 + 登録）
    public function action_create()
    {
        $data = array('error' => '');

        // POST送信（登録ボタン押した）
        if (\Input::method() === 'POST')
        {
            $type     = (int)\Input::post('type');          // 0=支出, 1=収入
            $amount   = (int)\Input::post('amount');        // 金額
            $category = trim((string)\Input::post('category'));
            $date     = (string)\Input::post('date');       // YYYY-MM-DD
            $memo     = trim((string)\Input::post('memo')); // 任意

            // 必須チェック
            if ($amount <= 0 || $category === '' || $date === '')
            {
                $data['error'] = '必須項目が未入力です（※金額は1円以上）';
                return \Response::forge(\View::forge('transactions/create', $data));
            }

            // typeチェック（0か1だけ許可）
            if ($type !== 0 && $type !== 1)
            {
                $data['error'] = '収支の種類が不正です';
                return \Response::forge(\View::forge('transactions/create', $data));
            }

            // ログイン中ユーザーID
            $user_id = (int)\Session::get('user_id');

            // DB登録
            \DB::insert('transactions')->set(array(
                'user_id'   => $user_id,
                'type'      => $type,
                'amount'    => $amount,
                'category'  => $category,
                'date'      => $date,
                'memo'      => ($memo === '') ? null : $memo,
                'created_at' => \DB::expr('NOW()'),
                'updated_at' => \DB::expr('NOW()'),
            ))->execute();

            // 登録後：ひとまずホームへ（一覧作ったら一覧に変えてOK）
            return \Response::redirect('/');
        }

        // GET（画面表示）
        return \Response::forge(\View::forge('transactions/create', $data));
    }
}