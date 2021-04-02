<?php

class JobsImporter
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

    public function cleanDB() {
        /* remove existing items */
        $this->db->exec('DELETE FROM job');
    }

    public function importJobsRJ($file)
    {
        /* parse XML file */
        $xml = simplexml_load_file($file);

        /* import each item */
        $count = 0;
        foreach ($xml->item as $item) {
            $stmt = $this->db->exec('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES ('
                . '\'' . addslashes($item->ref) . '\', '
                . '\'' . addslashes($item->title) . '\', '
                . '\'' . addslashes($item->description) . '\', '
                . '\'' . addslashes($item->url) . '\', '
                . '\'' . addslashes($item->company) . '\', '
                . '\'' . addslashes($item->pubDate) . '\')'
            );
            if (!$stmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($this->db->errorInfo());
            }
            $count++;
        }
        return $count;
    }

    public function importJobsJT($file)
    {
        /* parse XML file */
        $xml = simplexml_load_file($file);

        /* import each item */
        $count = 0;
        foreach ($xml->offer as $item) {
            $stmt = $this->db->exec('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES ('
                . '\'' . addslashes($item->reference) . '\', '
                . '\'' . addslashes($item->title) . '\', '
                . '\'' . addslashes($item->description) . '\', '
                . '\'' . addslashes($item->link) . '\', '
                . '\'' . addslashes($item->companyname) . '\', '
                . '\'' . addslashes(date('Y/m/d', strtotime(substr($item->publisheddate->asXML(), 15, 28)))) . '\')'
            );
            if (!$stmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($this->db->errorInfo());
            }
            $count++;
        }
        return $count;
    }
}
