<?php

namespace Models;

use \App\Model as Model;

/*
 * Home Class 
 */
class Home extends Model{
    
    /**
     * Constructor
     *
     * @return $this
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @param string $query takes an sql query as an argument
     *
     * @param string $type takes ARRAY_CON || OBJECT_CON as arguments
     *
     * @return $arr
     */
    private function transform($query, $type)
    {
        $arr = array();
        if($type == 'ARRAY_CON'){
            while( $data = $query->fetch_array(MYSQLI_ASSOC)){
                $arr[] = $data;
            }
        }
        elseif($type == 'OBJECT_CON'){
            while( $data = $query->fetch_object()){
                $arr[] = $data;
            }
        }
        else{
            $arr = 'Invalid Parameters';
        }
        return $arr;
    }
    /**
     * @param string $table
     *
     * @param array $data
     * 
     * @return $this
     */
    public function insert($table, $data)
    {
        foreach( array_keys($data) as $key )
        {
            $fields[] = "`$key`";
            $values[] = "'" .$data[$key] . "'";
        }
        $fields = implode(",", $fields);
        $values = implode(",", $values);
        $sql = "INSERT INTO `$table`($fields) VALUES ($values)";
        $this->db->set_charset("utf8"); // UTF8 settings
        $this->db->query($sql);
        return $this->db->insert_id;
    }
    
    /**
     * @param array $options Ex: ['table' => 'test', 'column' => 'id', 'value' => 1]
     *
     * @param array $data Ex: ['name' = > 'Lighty Framework v.1.0']
     *
     * @param constant $cookie default = 'CN'
     *
     * @return $this
     */
    public function update($options, $data, $cookie = 'CN')
    {
        $sql = "UPDATE ".$options['table']." SET ";
        foreach($data as $key => $value)
        {
            $sql .= $key."='". $value."', ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE `".$options['column']."` = '".$options['value']."' ";
        $this->db->query($sql);
    }
   
    /**
     * @param string $table
     *
     * @param string $type default = 'OBJECT_CON'
     *
     * @return $arr
     */
    public function fetchAll($table, $type = 'OBJECT_CON')
    {
        $sql = "SELECT * FROM `$table`";
        $query = $this->db->query($sql);
        $arr = $this->transform($query, $type);
        return $arr;
    }

    /**
     * @param array $data  Ex: ['table' => 'test', 'column' => 'id', 'value' => 1]
     *
     * @return $data_obj
     */
    public function first($data)
    {
        $sql = "SELECT * FROM ".$data['table']." WHERE `".$data['colummn']."` = '".$data['value']."' ";
        $query = $this->db->query($sql);
        if($data['type'] == 'array'){
            $data_obj = $query->fetch_array(MYSQLI_ASSOC);
        }
        elseif($data['type'] == 'object'){
            $data_obj = $query->fetch_object();
        }
        else{
            $data_obj = 'Invalid parameters!';
        }
        return $data_obj;
    }

    /**
     * Any other queries go here
     */
}