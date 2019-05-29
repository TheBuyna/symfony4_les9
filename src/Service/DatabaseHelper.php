<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class DatabaseHelper
{
    private $conn;
    private $sqllogger;

    public $result;
    public $rows;
    public $new_id;

    public function __construct(LoggerInterface $sqllogger)
    {
        $this->conn = new \mysqli("localhost","root", "steven123", "voetbal");
        $this->sqllogger = $sqllogger;
    }

    public function exec(string $sql)
    {
        //log to sql.log
        $this->sqllogger->info( $sql, array( $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ) );

        $this->rows = array();

        //SQL uitvoeren
        $this->result = $this->conn->query($sql);

        //als er rijen zijn, die opladen in $this->rows
        if ( is_object($this->result) AND $this->result->num_rows > 0 )
        {
            while ( $row = $this->result->fetch_assoc()) { $this->rows[] = $row; }
        }

        //als er een nieuw id is, dat laden in $this->new_id
        if ( $this->conn->insert_id ) $this->new_id = $this->conn->insert_id;

        return $this->result;
    }
}