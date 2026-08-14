<?php

$sql = file_get_contents(__DIR__ . '/suntri.sql');
if (!$sql) {
    die("Could not read suntri.sql");
}

// 1. Extract only INSERT INTO statements
preg_match_all('/INSERT INTO `([^`]+)` \(([^)]+)\) VALUES\s*(.*?);/s', $sql, $matches);

$tables = $matches[1];
$columns_raw = $matches[2];
$values_raw = $matches[3];

$skip_tables = ['migrations', 'jobs', 'job_batches', 'failed_jobs', 'cache', 'cache_locks', 'sessions', 'password_reset_tokens'];

$postgres_sql = "";

for ($i = 0; $i < count($tables); $i++) {
    $table = $tables[$i];
    if (in_array($table, $skip_tables)) continue;

    $columns = str_replace('`', '"', $columns_raw[$i]); // Use double quotes for columns in Postgres
    $values = $values_raw[$i];
    
    $postgres_sql .= "INSERT INTO \"{$table}\" ({$columns}) VALUES {$values};\n";
}

file_put_contents(__DIR__ . '/database/suntri_postgres.sql', $postgres_sql);
echo "Postgres SQL Generated!\n";
