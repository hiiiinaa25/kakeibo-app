<?php

class Model_Transaction
{
    public static function get_columns()
    {
        static $columns = null;

        if ($columns === null)
        {
            $columns = \DB::list_columns('transactions');
        }

        return $columns;
    }

    public static function find_by_user_id($user_id)
    {
        $columns = self::get_columns();
        $selects = array('id', 'user_id', 'type', 'amount', 'category');

        if (isset($columns['created_at']))
        {
            $selects[] = 'created_at';
        }

        if (isset($columns['updated_at']))
        {
            $selects[] = 'updated_at';
        }

        if (isset($columns['date']))
        {
            $selects[] = 'date';
        }

        if (isset($columns['memo']))
        {
            $selects[] = 'memo';
        }

        $query = \DB::select_array($selects)
            ->from('transactions')
            ->where('user_id', '=', (int) $user_id);

        if (isset($columns['deleted_at']))
        {
            $query->where('deleted_at', 'is', null);
        }

        if (isset($columns['date']))
        {
            $query->order_by('date', 'desc');
        }
        elseif (isset($columns['created_at']))
        {
            $query->order_by('created_at', 'desc');
        }

        $rows = $query
            ->order_by('id', 'desc')
            ->execute()
            ->as_array();

        foreach ($rows as &$row)
        {
            foreach ($row as $key => $value)
            {
                if ($value === null)
                {
                    $row[$key] = '';
                }
            }
        }
        unset($row);

        return $rows;
    }

    public static function normalize_type_for_db($type, array $columns)
    {
        if ( ! isset($columns['type']))
        {
            return (int) $type;
        }

        $type = (int) $type;
        $column = $columns['type'];

        if (isset($column['type']) && $column['type'] === 'int')
        {
            return $type;
        }

        if (
            isset($column['data_type'], $column['options'])
            && ($column['data_type'] === 'enum' || $column['data_type'] === 'set')
            && is_array($column['options'])
        ) {
            $options = $column['options'];

            if (in_array((string) $type, $options, true))
            {
                return (string) $type;
            }

            $map = array(
                0 => array('支出', 'expense', 'outgo', 'debit', 'cost'),
                1 => array('収入', 'income', 'earning', 'credit', 'revenue'),
            );

            foreach ($map[$type] as $candidate)
            {
                foreach ($options as $option)
                {
                    if (mb_strtolower($option, 'UTF-8') === mb_strtolower($candidate, 'UTF-8'))
                    {
                        return $option;
                    }
                }
            }

            if (count($options) >= 2)
            {
                return $options[$type];
            }
        }

        return (string) $type;
    }

    public static function label_for_type($value)
    {
        $v = mb_strtolower(trim((string) $value), 'UTF-8');

        if ($v === '0' || $v === '支出' || $v === 'expense' || $v === 'outgo' || $v === 'debit' || $v === 'cost')
        {
            return '支出';
        }

        if ($v === '1' || $v === '収入' || $v === 'income' || $v === 'earning' || $v === 'credit' || $v === 'revenue')
        {
            return '収入';
        }

        return (string) $value;
    }

    public static function soft_delete_by_id($id, $user_id)
    {
        $columns = self::get_columns();
        if ( ! isset($columns['deleted_at']))
        {
            return false;
        }

        $target = \DB::select('id', 'deleted_at')
            ->from('transactions')
            ->where('id', '=', (int) $id)
            ->where('user_id', '=', (int) $user_id)
            ->execute()
            ->current();

        if ( ! $target)
        {
            return false;
        }

        $update_data = array(
            'deleted_at' => date('Y-m-d H:i:s'),
        );

        if (isset($columns['updated_at']))
        {
            $update_data['updated_at'] = date('Y-m-d H:i:s');
        }

        $affected = \DB::update('transactions')
            ->set($update_data)
            ->where('id', '=', (int) $id)
            ->where('user_id', '=', (int) $user_id)
            ->execute();

        return ((int) $affected > 0);
    }

    public static function find_one_by_id($id, $user_id)
    {
        $columns = self::get_columns();
        $selects = array('id', 'user_id', 'type', 'amount', 'category');

        if (isset($columns['date']))
        {
            $selects[] = 'date';
        }
        if (isset($columns['memo']))
        {
            $selects[] = 'memo';
        }
        if (isset($columns['created_at']))
        {
            $selects[] = 'created_at';
        }

        $query = \DB::select_array($selects)
            ->from('transactions')
            ->where('id', '=', (int) $id)
            ->where('user_id', '=', (int) $user_id);

        if (isset($columns['deleted_at']))
        {
            $query->where('deleted_at', 'is', null);
        }

        $row = $query->execute()->current();
        if ( ! $row)
        {
            return null;
        }

        foreach ($row as $k => $v)
        {
            if ($v === null)
            {
                $row[$k] = '';
            }
        }

        return $row;
    }

    public static function update_by_id($id, $user_id, array $payload)
    {
        $columns = self::get_columns();
        $update_data = array();

        if (isset($payload['type']))
        {
            $update_data['type'] = self::normalize_type_for_db((int) $payload['type'], $columns);
        }
        if (isset($payload['amount']))
        {
            $update_data['amount'] = (int) $payload['amount'];
        }
        if (isset($payload['category']))
        {
            $update_data['category'] = (string) $payload['category'];
        }
        if (isset($payload['date']) && isset($columns['date']))
        {
            $update_data['date'] = (string) $payload['date'];
        }
        if (isset($columns['memo']) && array_key_exists('memo', $payload))
        {
            $memo = (string) $payload['memo'];
            $update_data['memo'] = ($memo === '') ? null : $memo;
        }
        if (isset($columns['updated_at']))
        {
            $update_data['updated_at'] = date('Y-m-d H:i:s');
        }

        if (empty($update_data))
        {
            return false;
        }

        $query = \DB::update('transactions')
            ->set($update_data)
            ->where('id', '=', (int) $id)
            ->where('user_id', '=', (int) $user_id);

        if (isset($columns['deleted_at']))
        {
            $query->where('deleted_at', 'is', null);
        }

        $affected = $query->execute();

        return ((int) $affected >= 0);
    }
}
