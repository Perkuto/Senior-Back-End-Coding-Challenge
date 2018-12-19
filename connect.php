<?php

// Coded by Bond on a cold December night in 2018
// Project: Koto
// Description: Simple API for photo sharing (https://github.com/Perkuto/Senior-Back-End-Coding-Challenge)

// db pdo functions
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