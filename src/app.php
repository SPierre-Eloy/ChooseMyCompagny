<?php

/************************************
Entry point of the project.
To be run from the command line.
************************************/

define('SQL_HOST', 'mariadb');
define('SQL_USER', 'root');
define('SQL_PWD', 'root');
define('SQL_DB', 'cmc_db');
define('RESSOURCES_DIR', __DIR__ . '/../resources/');


function __autoload(string $classname) {
    include_once(__DIR__ . '/' . $classname . '.php');
}

echo sprintf("Starting DB...\n");
$jobsImporter = new JobsImporter(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);

echo sprintf("Cleaning...\n");
$jobsImporter->cleanDB();

echo sprintf("Starting Import...\n");
$count = $jobsImporter->importJobsRJ(RESSOURCES_DIR . 'regionsjob.xml');
echo sprintf("> %d jobs imported from RegionsJob.\n", $count);

$count = $jobsImporter->importJobsJT(RESSOURCES_DIR . 'jobteaser.xml');
echo sprintf("> %d jobs imported from JobTeaser.\n", $count);

/* list jobs */
$jobsLister = new JobsLister(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);
$jobs = $jobsLister->listJobs();

echo sprintf("> all jobs (%d):\n", count($jobs));
foreach ($jobs as $job) {
    echo sprintf(" %d: %s - %s - %s \n", $job['id'], $job['reference'], $job['title'], $job['publication']);
}


echo sprintf("Done.\n");
