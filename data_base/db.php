<?php
/**
 * Created by PhpStorm.
 * User: daniilskli
 * Date: 26.09.21
 * Time: 20:37
 */



class MONO_DB
{
    private static $init = false;
    private static $connection;
    private static $tprefix = "";

    /* Get connection */
    private static function start()
    {
        $MONO_HOST = MONO_JSON::Decode(CORE . "data/connect.json");

        self::$tprefix = $MONO_HOST["table_prefix"];
        self::$connection = new PDO('mysql:dbname='.$MONO_HOST["db"].';host='.$MONO_HOST["host"] . ";charset=utf8", $MONO_HOST["login"], $MONO_HOST["password"]);
    }



    /* Get connection pdo */
    public static function getPDO()
    {
        return self::$connection;
    }



    /* Drop table */
    public static function drop($table)
    {
        /* Check connection */
        self::$init ?: self::start();

        $sql = "DROP TABLE " . self::$tprefix . $table;
        self::$connection->query($sql);
    }

    /* create table */
    public static function create($table, $func)
    {
        /* Check connection */
        self::$init ?: self::start();

        $tableTemplate = self::createTable(self::$tprefix . $table);
        $func($tableTemplate);

        self::$connection->query($tableTemplate->sql());
    }

    private static function createTable($table)
    {
        return new class($table)
        {
            private $dtable = "";
            private $columns = array();

            public function __construct($table)
            {
                $this->dtable = $table;
                $this->id();
            }

            /* get sql */
            public function sql()
            {
                $this->id(); // lock to changes
                $sql = "";

                foreach ($this->columns as $col => $type)
                {
                    $sql .= $col . " " . $type . ",";
                }

                $sql = substr($sql, 0, strlen($sql) - 1);

                return "CREATE TABLE " . $this->dtable . "(" . $sql . ")";
            }



            /* id */
            private function id()
            {
                $this->columns["id"] = "INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
            }

            /* string */
            public function string($name)
            {
                $this->columns[$name] = "TEXT";
            }

            /* int */
            public function integer($name)
            {
                $this->columns[$name] = "INT";
            }

            /* timestamp */
            public function timestamp($name)
            {
                $this->columns[$name] = "TIMESTAMP";
            }

            /* json */
            public function json($name)
            {
                $this->columns[$name] = "JSON";
            }
        };
    }




    /* table - work with table */
    public static function table($table)
    {
        /* Check connection */
        self::$init ?: self::start();

        $table = self::createQuery(self::$tprefix . $table);
        return $table;
    }



    /* Create query class to usage db functions */
    private static function createQuery($table)
    {
        $connection = self::$connection;



        return new class($table, $connection)
        {
            private $mode = "SELECT";

            private $connection;
            private $dtable = "";

            private $dwhere = "";
            private $dorderBy = "";
            private $dorderType = "";

            private $ddataarr;

            public function __construct($table, $connection)
            {
                $this->connection = $connection;
                $this->dtable = $table;
            }



            /* get sql string */
            public function sql()
            {
                if ($this->mode == "SELECT")
                    $sql = "SELECT * FROM " . $this->dtable . " " . $this->dwhere . " " . $this->dorderBy . " " . $this->dorderType;

                else if ($this->mode == "UPDATE") {
                    $sql = "UPDATE " . $this->dtable . " SET";

                    foreach ($this->ddataarr as $var => $value) {
                        $sql .= " " . $var . "='" . $value . "'";
                    }

                    $sql .= " " . $this->dwhere;
                }

                else if ($this->mode == "INSERT") {
                    $sql = "INSERT INTO " . $this->dtable . "(";

                    $col = "";
                    $val = "";
                    foreach ($this->ddataarr as $var => $value) {
                        $col .= $var . ",";
                        $val .= "'" . $value . "',";
                    }
                    $col = substr($col, 0, strlen($col) - 1);
                    $val = substr($val, 0, strlen($val) - 1);

                    $sql .= $col . ") VALUES (" . $val . ")";
                }

                else if ($this->mode == "LAST")
                    $sql = "SELECT MAX(`id`) FROM " . $this->dtable;

                else if ($this->mode == "DELETE")
                    $sql = "DELETE FROM " . $this->dtable . " " . $this->dwhere;



                return $sql;
            }



            /* query */
            private function query() {
                $sql = $this->sql();

                $result = $this->connection->query($sql);

                if (!$result) { return array(); }

                return $result->fetchAll(PDO::FETCH_ASSOC);
            }

            /* get data */
            public function get()
            {
                $this->mode = "SELECT";

                return $this->query();
            }

            /* get data for foreach */
            public function each($func)
            {
                $data = $this->get();

                foreach ($data as $row) {
                    $func($row);
                }

                return $this;
            }

            /* update data */
            public function update($arr)
            {
                $this->mode = "UPDATE";

                $this->ddataarr = $arr;

                $this->query();

                return $this;
            }

            /* insert data */
            public function insert($arr)
            {
                $this->mode = "INSERT";

                $this->ddataarr = $arr;

                $this->query();

                return $this;
            }

            /* get last id */
            public function lastID()
            {
                $this->mode = "LAST";

                $last = $this->query();
                if (isset($last[0]["MAX(`id`)"]))
                    return $last[0]["MAX(`id`)"];

                else return 0;
            }

            /* delete data */
            public function delete()
            {
                $this->mode = "DELETE";

                $this->query();
                return $this;
            }



            /* Set where var */
            public function where($var = null, $value = null)
            {
                if (($var || $value) == null) {
                    $this->dwhere = "";
                    return $this;
                }

                ($this->dwhere == "") ? $this->dwhere = "WHERE " : $this->dwhere .= "AND ";

                $this->dwhere .= $var . " = '" . $value . "'";

                return $this;
            }

            /* Set column var */
//            public function column($col)
//            {
//
//            }

            /* get first */
            public function first()
            {
                $get = $this->get();
                if (isset($get[0]))
                    return $get[0]; // TODO: optimize with sql

                else
                    return $get;
            }

            /* get value from column */
            public function value($var)
            {
                $first = $this->first();
                if (isset($first[$var]))
                    return $first[$var]; // TODO: optimize with sql

                else
                    return $first;
            }

            /* orderby */
            public function orderBy($byColumn, $desc = false)
            {
                $this->dorderBy = "ORDER BY " . $byColumn;
                $this->dorderType = $desc ? "DESC" : "ASC";

                return $this;
            }
        };
    }
}