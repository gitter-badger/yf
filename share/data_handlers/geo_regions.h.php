<?php

$data = db()->get_all('SELECT * FROM '.db('geo_regions').' WHERE active="1"');
