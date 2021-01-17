<?php
$zone0 = file_get_contents('/sys/class/thermal/thermal_zone0/temp');
$temp  = $zone0/1000;
print(json_encode(['temp' => $temp,]));