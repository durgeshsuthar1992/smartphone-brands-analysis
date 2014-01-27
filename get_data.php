<?php
	include_once('config.php');
	$query=$db->query("SELECT key_name,count(key_name) as c,sum(key_score)  FROM senti group by key_name ");
	$pos=$db->query("SELECT key_name,count(key_name) as c,sum(key_score) FROM senti WHERE key_score>0 GROUP BY key_name ");
	$neg=$db->query("SELECT key_name,count(key_name) as c,sum(key_score) FROM senti WHERE key_score<0 GROUP BY key_name ");
	$neu=$db->query("SELECT key_name,count(key_name) as c,sum(key_score) FROM senti WHERE key_score=0 GROUP BY key_name ");
	$data=array(array(),array(),array(),array());
	while ($row=$query->fetch(PDO::FETCH_NUM)) {
		$data[0][] = $row;
	}
	while ($row=$pos->fetch(PDO::FETCH_NUM)) {
		$data[1][] = $row;
	}
	while ($row=$neg->fetch(PDO::FETCH_NUM)) {
		$data[2][] = $row;
	}
	while ($row=$neu->fetch(PDO::FETCH_NUM)) {
		$data[3][] = $row;
	}
	echo(json_encode($data));
	exit;
?>