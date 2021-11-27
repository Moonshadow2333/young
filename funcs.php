<?php

function replace($new,$old,$lat){
	if (empty($lat)) {
		return [];
	}
	$car = array_shift($lat);
	if ($old == $car) {
		return array_merge([$new],replace($new,$old,$lat));
	}else{
		return array_merge([$car],replace($new,$old,$lat));
	}
}

function member($a, array $lat)
{
    if (empty($lat)) {
        return false;
    } else {
        return array_shift($lat) == $a||member($a, $lat);
    }
}