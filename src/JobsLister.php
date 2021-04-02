<?php

class JobsLister
{
    private $db;

    public function __construct($host, $username, $password, $databaseName)
    {
        /* connect to DB */
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    public function listJobs()
    {
        $jobs = $this->db->query('SELECT id, reference, title, description, url, company_name, publication FROM job')->fetchAll(PDO::FETCH_ASSOC);
        return $jobs;
    }
}
