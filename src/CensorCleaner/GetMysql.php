<?php

namespace CensorCleaner;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

Class GetMysql implements IntGetterSetter
{
    /**
    * DB conntection
    * 
    * @var \Pixie\QueryBuilder\QueryBuilderHandler
    */
    private $connection;
    /**
    * Connection to db in construct
    * 
    * @param mixed $config - config array, examaple like default
    */
    function __construct(
        $config = array(
            'driver'    => 'mysql', // Db driver
            'host'      => 'localhost',
            'database'  => '',
            'username'  => '',
            'password'  => '',
            'charset'   => 'utf8', // Optional
            'options'   => array( // PDO constructor options, optional
                \PDO::ATTR_TIMEOUT => 120,
            ),
        )
    ){
        /**Using Pixie ORM **/
        $connection = new \Pixie\Connection('mysql', $config);
        $this->connection =  new \Pixie\QueryBuilder\QueryBuilderHandler($connection);
    }
    /**
    * Get data from MySQL
    * 
    * @param mixed $config['query'] - Query string
    * @return \stdClass
    */
    function get_data($config){
        $query = $this->connection->query($config['query']);        
        return $query->setFetchMode(\PDO::FETCH_ASSOC)->get();
    }
    /**
    * Changing datat
    * 
    * @param mixed @param mixed $config['query'] - Query string
    * @return \stdClass
    */
    function change_data($config){
        $query = $this->connection->query($config['query']);        
        return $query->get();      
    }
    /**
    * Inserting new data
    * 
    * @param mixed $config['query'] - Query string
    * @return \stdClass
    */
    function insert_data($config){
        $query = $this->connection->query($config['query']);        
        return $query->get();
    }
    /**
    * Deleting data
    * 
    * @param mixed $config['query'] - Query string
    * @return \stdClass
    */
    function delete_data($config){
        $query = $this->connection->query($config['query']);        
        return $query->get();
    }
}