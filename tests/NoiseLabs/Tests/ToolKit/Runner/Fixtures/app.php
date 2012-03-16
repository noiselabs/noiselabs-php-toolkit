#!/usr/bin/php
<?php

    echo 'testing string';

    $f = fopen('php://stderr','w');
    fwrite($f, 'these are errors');
    fclose($f);

    exit(254);
?>
