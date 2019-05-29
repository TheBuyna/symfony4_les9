<?php


namespace App\Service;


class DatabaseHelper
{
    public function VerbindMetMySql()
    {
        $conn = new \mysqli("localhost","root", "buynsql", "voetbal");
        return $conn;
    }

    public function execSqlCommand($command)
    {
        $result = $this->VerbindMetMySql()->query($command);
        return $result;
    }
}