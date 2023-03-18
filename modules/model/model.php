<?php

namespace Model;
require_once $basePath.'modules/helpers.php';

class BaseModel implements \Iterator
{
    protected const TABLE_NAME = '';
    protected const DEFAULT_ORDER = '';
    protected const RELATIONS = [];

    static private $connection = NULL;
    static private $connection_count = 0;


    function __construct()
    {
        if (!self::$connection)
        {
            self::$connection = \Helpers\connectToDb();
            
            self::$connection_count++;
        }
    }

    
    function __destruct()
    {
        self::$connection_count--;
        if (self::$connection_count == 0)
        {
            self::$connection = NULL;
        }
    }

    
    private $query = NULL;
    
    public function run($sql, $params = NULL)
    {
        if($this->query)
        {
            $this->query->closeCursor();
        }

        $this->query = self::$connection->prepare($sql);

        if($params)
        {
            foreach($params as $key => $value)
            {
                $k = (is_integer($key)) ? $key + 1 : $key;
                switch(gettype($value))
                {
                    case 'integer': 
                        $t = \PDO::PARAM_INT;
                        break;
                    case 'boolean':
                        $t = \PDO::PARAM_BOOL;
                        break;
                    case 'NULL':
                        $t = \PDO::PARAM_NULL;
                        break;
                    default:
                        $t = \PDO::PARAM_STR;
                }

                $this->query->bindValue($k, $value, $t);
            }
        }
        $this->query->execute();
    }

    
    public function select($fields = '*', $links = NULL, $where = '', $params = NULL, $order = '', $offset = NULL, $limit = NULL, $group = '', $having = '')
    {
        $s = 'SELECT '.$fields.' FROM '.static::TABLE_NAME;
        if($links)
        {
            foreach($links as $ext_table)
            {
                $relation = static::RELATIONS[$ext_table];
                $s .= ' '.(key_exists('type', $relation) ? $relation[type] : 'INNER').' JOIN '.$ext_table.' ON '.static::TABLE_NAME.'.'.$relation['external'].' = '.$ext_table.'.'.$relation['primary'];
            }
        }
        
        if($where)
        {
            $s .= ' WHERE '.$where;
        } 
        
        if($group)
        {
            $s .= ' GROUP BY '.$group;
            if ($having)
            {
                $s .= ' HAVING '.$having;
            }
        }
        
        if($order)
        {
            $s .= ' ORDER BY '.$order;
        } else 
        {
            $s .= ' ORDER BY '.static::DEFAULT_ORDER;
        }
        
        if($limit && $offset !== NULL)
        {
            $s .= ' LIMIT '.$offset.', '.$limit;
        }
        
        $s .= ';';

        $this->run($s, $params);
    }


    private $record = FALSE;

    function rewind() : void
    {
        $this->record = $this->query->fetch(\PDO::FETCH_ASSOC);
    }

    function current() : mixed
    {
        return $this->record;
    }

    function key() : mixed
    {
        return 0;
    }

    function next() : void
    {
        $this->record = $this->query->fetch(\PDO::FETCH_ASSOC);
    }

    function valid() : bool
    {
        return $this->record !== FALSE;
    }


    /**
     * get record with standart params
     */
    public function getRecord($fields = '*', $links = NULL, $where = '', $params = NULL)
    {
        $this->record = FALSE;
        $this->select($fields, $links, $where, $params);
        return $this->query->fetch(\PDO::FETCH_ASSOC);      
    }

    /**
     * get record from db with condition WHERE
     */
    public function get($value, $key_field = 'id', $fields = '*', $links = NULL)
    {
        return $this->getRecord($fields, $links, $key_field.' = ?', [$value]);
    }

    /**
     * get record with getRecord(), if there isn't record we catch Exception
     */
    public function getOr404($value, $key_field = 'id', $fields = '*', $links = NULL)
    {
        $rec = $this->get($value, $key_field, $fields, $links);
        if($rec)
        {
            return $rec;
        } else 
        {
            throw new \Page404Exception;
        }
    }

    protected function beforeInsert(&$fields) {}

    public function insert($fields) 
    {
        static::beforeInsert($fields);
        $s = 'INSERT INTO '.static::TABLE_NAME;
        $s2 = $s1 = '';
        foreach($fields as $n => $v)
        {
            if($s1) {
                $s1 .= ', ';
                $s2 .= ', ';
            }
            $s1 .= $n;
            $s2 .= ':'.$n;
        }
        $s .= ' ('.$s1.') VALUES ('.$s2.');';
        $this->run($s, $fields);
        $id = self::$connection->lastInsertId();
        return $id;
    }

    protected function beforeUpdate(&$fields, $value, $key_field = 'id') {}
    
    public function update($fields, $value, $key_field = 'id') 
    {
        static::beforeUpdate($fields, $value, $key_field);
        $s = 'UPDATE '.static::TABLE_NAME.' SET ';
        $s1 = '';
        foreach($fields as $n => $v) 
        {
            if($s1) {
                $s1 .= ', ';
            }
            $s1 .= $n.' =:'.$n;
        }
        $s .= $s1.' WHERE '.$key_field.' = :__key;';
        $fields['__key'] = $value;
        $this->run($s, $fields);
    }

    protected function beforeDelete($value, $key_field = 'id') {}

    public function delete($value, $key_field = 'id')
    {
        static::beforeDelete($value, $key_field);
        $s = 'DELETE FROM '.static::TABLE_NAME;
        $s .= ' WHERE '.$key_field.' = ?';
        $this->run($s, [$value]);
    }

}

?>