<?php

// Project: Koto
// Description: Simple photo sharing platform with an open source REST API
// Coded by Bond on a cold December night in 2018

// db PDO functions
class DB {
        private $pdo;
        public function __construct($host, $dbname, $username, $password) {
                $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo = $pdo;
        }
        public function query($query, $params = array()) {
                $statement = $this->pdo->prepare($query);
                $statement->execute($params);
                if (explode(' ', $query)[0] == 'SELECT') {
                $data = $statement->fetchAll();
                return $data;
                }
        }
}

// db credentials, host, dbname, username, password
$db = new DB("localhost", "koto", "koto", "password");
