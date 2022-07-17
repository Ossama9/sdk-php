<?php

namespace App\Model;

class BaseModel
{
    protected string|false $table;
    private string $path = 'data/';
    private string $fileName = '';
    private ?array $database = null;

    public function __construct()
    {
        $getCalledClassExploded = explode("\\", strtolower(get_called_class())); // App\Model\User
        $this->table = end($getCalledClassExploded);


        if (file_exists($this->path . strtolower($this->table) . 's.db')) {
            $this->fileName = $this->path . strtolower($this->table) . 's.db';
            $this->database = $this->readDatabase($this->path . strtolower($this->table) . 's.db');
        } else {
            die('Database not found');
        }

    }


    private function readDatabase($filename)
    {
        $data = file($filename);

        return array_map(fn($line) => json_decode($line, true), $data);
    }

    private function writeDatabase($data)
    {

        file_put_contents($this->fileName, implode(
            "\n",
            array_map(
                fn($line) => json_encode($line),
                $data
            )
        ));
    }

    private function insertData($data)
    {
        $database = $this->database;
        $database[] = $data;
        $this->writeDatabase($database);
    }


    /*private function insertCode($code)
    {
        $this->insertData('./data/codes.db', $code);
    }
    private function insertToken($token)
    {
        $this->insertData('./data/tokens.db', $token);
    }*/

    public function findOne()
    {
        $colums = get_object_vars($this);
        $varToExclude = get_class_vars(get_class());
        $colums = array_diff_key($colums, $varToExclude);
        $colums = array_filter($colums);


        $result = array_values(array_filter(
            $this->database,
            fn($app) => count(array_intersect_assoc($app, $colums)) === count($colums)
        ));


        return $result[0] ?? null;

    }

    private function findBy($filename, $criteria)
    {
        $database = $this->readDatabase($filename);

        $result = array_values(array_filter(
            $database,
            fn($app) => count(array_intersect_assoc($app, $criteria)) === count($criteria)
        ));

        return $result[0] ?? null;
    }

    private function findAppBy($criteria)
    {
        return $this->findBy('./data/apps.db', $criteria);
    }

    private function findCodeBy($criteria)
    {
        return $this->findBy('./data/codes.db', $criteria);
    }

    private function findTokenBy($criteria)
    {
        return $this->findBy('./data/tokens.db', $criteria);
    }

    private function findUserBy($criteria)
    {
        return $this->$this->findBy('./data/users.db', $criteria);
    }


    public function save()
    {
        $colums = get_object_vars($this);
        $varToExclude = get_class_vars(get_class());
        $colums = array_diff_key($colums, $varToExclude);


        $data = $colums;


        $this->insertData($data);

    }
}