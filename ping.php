<?php

// Define filename to read.
$filename = '/dev/shm/ping.webnix';

// Empty set of hosts ping result.
$pings = [];

// Set columns names.
$column_names = array('ip','name','status');

// Read file and process to json.
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $pings[] = array_combine($column_names, $data);
    }
    fclose($handle);
}
print(json_encode($pings));