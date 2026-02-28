<?php

class Controller_Transactions extends Controller_Base
{
    public function action_index()
    {
        $user_id = (int) \Session::get('user_id');
        $columns = \Model_Transaction::get_columns();
        $transactions = \Model_Transaction::find_by_user_id($user_id);
        $income_total = 0;
        $expense_total = 0;

        foreach ($transactions as $row)
        {
            $amount = (int) $row['amount'];
            $label = \Model_Transaction::label_for_type($row['type']);
            if ($label === '収入')
            {
                $income_total += $amount;
            }
            elseif ($label === '支出')
            {
                $expense_total += $amount;
            }
        }

        $data = array(
            'transactions' => $transactions,
            'income_total' => $income_total,
            'expense_total' => $expense_total,
            'balance' => $income_total - $expense_total,
            'success'      => (string) \Session::get_flash('success', ''),
            'can_soft_delete' => isset($columns['deleted_at']),
        );

        return \Response::forge(\View::forge('transactions/index', $data));
    }

    public function action_create()
    {
        $data = array('error' => '');

        if (\Input::method() === 'POST')
        {
            if ( ! \Security::check_token())
            {
                $data['error'] = '不正なリクエストです';
                return \Response::forge(\View::forge('transactions/create', $data));
            }

            $type     = (int) \Input::post('type');
            $amount   = (int) \Input::post('amount');
            $category = trim((string) \Input::post('category'));
            $date     = trim((string) \Input::post('date'));
            $memo     = trim((string) \Input::post('memo'));

            if ($amount <= 0 || $category === '' || $date === '')
            {
                $data['error'] = '必須項目を入力してください。';
                return \Response::forge(\View::forge('transactions/create', $data));
            }

            if ($type !== 0 && $type !== 1)
            {
                $data['error'] = '収支の種別が不正です。';
                return \Response::forge(\View::forge('transactions/create', $data));
            }

            $date_time = \DateTime::createFromFormat('Y-m-d', $date);
            if ($date_time === false || $date_time->format('Y-m-d') !== $date)
            {
                $data['error'] = '日付の形式が不正です。';
                return \Response::forge(\View::forge('transactions/create', $data));
            }

            $user_id = (int) \Session::get('user_id');
            $columns = \Model_Transaction::get_columns();
            $now = date('Y-m-d H:i:s');

            $insert_data = array(
                'user_id'    => $user_id,
                'type'       => \Model_Transaction::normalize_type_for_db($type, $columns),
                'amount'     => $amount,
                'category'   => $category,
            );

            if (isset($columns['date']))
            {
                $insert_data['date'] = $date;
                $insert_data['created_at'] = $now;
            }
            else
            {
                $insert_data['created_at'] = $date . ' 00:00:00';
            }

            if (isset($columns['memo']))
            {
                $insert_data['memo'] = ($memo === '') ? null : $memo;
            }

            if (isset($columns['updated_at']))
            {
                $insert_data['updated_at'] = $now;
            }

            \DB::insert('transactions')->set($insert_data)->execute();

            return \Response::redirect('transactions');
        }

        return \Response::forge(\View::forge('transactions/create', $data));
    }

    public function action_delete($id = null)
    {
        if (\Input::method() !== 'POST')
        {
            return \Response::forge(
                json_encode(array('ok' => false, 'message' => 'Method Not Allowed')),
                405,
                array('Content-Type' => 'application/json; charset=utf-8')
            );
        }

        if ( ! \Security::check_token())
        {
            return \Response::forge(
                json_encode(array('ok' => false, 'message' => '不正なリクエストです')),
                400,
                array('Content-Type' => 'application/json; charset=utf-8')
            );
        }

        if ($id === null || !ctype_digit((string) $id))
        {
            return \Response::forge(
                json_encode(array('ok' => false, 'message' => 'Invalid ID')),
                400,
                array('Content-Type' => 'application/json; charset=utf-8')
            );
        }

        $user_id = (int) \Session::get('user_id');
        $columns = \Model_Transaction::get_columns();
        if ( ! isset($columns['deleted_at']))
        {
            return \Response::forge(
                json_encode(array('ok' => false, 'message' => 'deleted_at カラムが無いため論理削除できません。')),
                400,
                array('Content-Type' => 'application/json; charset=utf-8')
            );
        }

        $deleted = \Model_Transaction::soft_delete_by_id((int) $id, $user_id);

        if ( ! $deleted)
        {
            return \Response::forge(
                json_encode(array('ok' => false, 'message' => '削除できませんでした。')),
                400,
                array('Content-Type' => 'application/json; charset=utf-8')
            );
        }

        return \Response::forge(
            json_encode(array('ok' => true)),
            200,
            array('Content-Type' => 'application/json; charset=utf-8')
        );
    }

    public function action_edit($id = null)
    {
        if ($id === null || !ctype_digit((string) $id))
        {
            return \Response::redirect('transactions');
        }

        $user_id = (int) \Session::get('user_id');
        $row = \Model_Transaction::find_one_by_id((int) $id, $user_id);
        if ( ! $row)
        {
            return \Response::redirect('transactions');
        }

        $date = isset($row['date']) && $row['date'] !== '' ? $row['date'] : '';
        if ($date === '' && isset($row['created_at']) && $row['created_at'] !== '')
        {
            $date = substr($row['created_at'], 0, 10);
        }

        $data = array(
            'error' => '',
            'id' => (int) $row['id'],
            'form' => array(
                'type' => (\Model_Transaction::label_for_type($row['type']) === '収入') ? 1 : 0,
                'amount' => (int) $row['amount'],
                'category' => (string) $row['category'],
                'date' => $date,
                'memo' => isset($row['memo']) ? (string) $row['memo'] : '',
            ),
        );

        return \Response::forge(\View::forge('transactions/edit', $data));
    }

    public function action_update($id = null)
    {
        if (\Input::method() !== 'POST' || $id === null || !ctype_digit((string) $id))
        {
            return \Response::redirect('transactions');
        }

        if ( ! \Security::check_token())
        {
            $data = array(
                'error' => '不正なリクエストです',
                'id' => (int) $id,
                'form' => array(
                    'type' => (int) \Input::post('type'),
                    'amount' => (int) \Input::post('amount'),
                    'category' => trim((string) \Input::post('category')),
                    'date' => trim((string) \Input::post('date')),
                    'memo' => trim((string) \Input::post('memo')),
                ),
            );
            return \Response::forge(\View::forge('transactions/edit', $data));
        }

        $type     = (int) \Input::post('type');
        $amount   = (int) \Input::post('amount');
        $category = trim((string) \Input::post('category'));
        $date     = trim((string) \Input::post('date'));
        $memo     = trim((string) \Input::post('memo'));

        $data = array(
            'error' => '',
            'id' => (int) $id,
            'form' => array(
                'type' => $type,
                'amount' => $amount,
                'category' => $category,
                'date' => $date,
                'memo' => $memo,
            ),
        );

        if ($amount <= 0 || $category === '' || $date === '')
        {
            $data['error'] = '必須項目を入力してください。';
            return \Response::forge(\View::forge('transactions/edit', $data));
        }

        if ($type !== 0 && $type !== 1)
        {
            $data['error'] = '収支の種別が不正です。';
            return \Response::forge(\View::forge('transactions/edit', $data));
        }

        $date_time = \DateTime::createFromFormat('Y-m-d', $date);
        if ($date_time === false || $date_time->format('Y-m-d') !== $date)
        {
            $data['error'] = '日付の形式が不正です。';
            return \Response::forge(\View::forge('transactions/edit', $data));
        }

        $user_id = (int) \Session::get('user_id');
        $updated = \Model_Transaction::update_by_id((int) $id, $user_id, array(
            'type' => $type,
            'amount' => $amount,
            'category' => $category,
            'date' => $date,
            'memo' => $memo,
        ));

        if ( ! $updated)
        {
            $data['error'] = '更新できませんでした。';
            return \Response::forge(\View::forge('transactions/edit', $data));
        }

        return \Response::redirect('transactions');
    }
}
