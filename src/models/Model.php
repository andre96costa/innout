<?php

class Model
{
    protected static $tableName = '';
    protected static $columns = [];
    protected $values = [];

    public function __construct($arr, $sanitize = true) {
        $this->loadFromArray($arr, $sanitize);
    }

    public function loadFromArray($arr, $sanitize = true)
    {
        if ($arr) {
            foreach ($arr as $key => $value) {
                $cleanValue = $value;
                if ($sanitize && isset($cleanValue)) {
                    $cleanValue = strip_tags(trim($cleanValue));
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                }
                $this->$key = $cleanValue ;
            }
        }
    }

    public function __get($key)
    {
        return @$this->values[$key];
    }

    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function getValues()
    {
        return $this->values;    
    }

    public static function get($filters = [], $columns = '*')
    {
        $objects = [];
        $result = static::getResultSetFromSelect($filters, $columns);
        if ($result) {
            $class = get_called_class();
            while ($row = $result->fetch_assoc()) {
                array_push($objects, new $class($row));
            }
        }
        return $objects;
    }

    public static function getOne($filters = [], $columns = '*')
    {
        $class = get_called_class();
        $result = static::getResultSetFromSelect($filters, $columns);
        return $result ? new $class($result->fetch_assoc()) : null;
    }

    public static function getResultSetFromSelect($filters = [], $columns = '*')
    {
        $sql = "SELECT $columns FROM " . static::$tableName . static::getFilers($filters);
        
        $result = Database::getResultFromQuery($sql);
        if ($result->num_rows === 0) {
            return null;
        }else {
            return $result;
        }
    }

    public function insert()
    {
        $sql = "INSERT INTO " . static::$tableName . " (" . implode(',', static::$columns) . ") VALUES(";

        foreach (static::$columns as $column) {
            $sql .= static::getFormatedValue($this->$column) . ",";
        }

        $sql[strlen($sql) - 1] = ")";

        $id = Database::executeSql($sql);

        $this->id = $id;
    }

    public function update()
    {
        $sql = "UPDATE " . static::$tableName . " SET ";

        foreach (static::$columns as $column) {
            $sql .= " $column = " . static::getFormatedValue($this->$column) . ",";
        }

        $sql[strlen($sql) - 1] = " ";

        $sql .= "WHERE id = $this->id";

        Database::executeSql($sql);
    }

    private static function getFilers($filters)
    {
        $sql = '';
        if (count($filters) > 0) {
            $sql .= " WHERE 1 = 1";
            foreach ($filters as $column => $value) {
                if ($column === 'raw') {
                    $sql .= " AND $value";
                }else {
                    $sql .= " AND $column = " . static::getFormatedValue($value); 
                }
            }
        }

        return $sql;
    }

    public static function getCount($filters = [])
    {
        $result = static::getResultSetFromSelect($filters, 'count(*) as count');
        return $result->fetch_assoc()['count'];
    }

    public static function deleteById($id)
    {
        $sql = "DELETE FROM " . static::$tableName . " WHERE id = " . $id;
        Database::executeSql($sql);
    }

    private static function getFormatedValue($value) {
        if (is_null($value)) {
            return "null";
        }elseif(gettype($value) === 'string'){
            return "'$value'";
        }else {
            return $value;
        }
    }
}