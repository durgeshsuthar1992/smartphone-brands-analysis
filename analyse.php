<?php
	include_once('config.php');
	include_once('alchemyapi.php');

	$analyse=new alchemyAPI();
	$i=0;
	$sql=$db->query("SELECT tweet_text FROM tweets ORDER BY tweet_id ASC LIMIT 10001,800");
	$num=$sql->rowCount();
	$rows=array();
	while ($tweet=$sql->fetch(PDO::FETCH_ASSOC)) {
		$response = $analyse->keywords('text',$tweet["tweet_text"],array('sentiment'=>1));
		if($response['status']=='OK'){
			foreach ($response['keywords'] as $keyword) {
				//var_dump(strpos(strtolower($keyword['text']),'iphone'));
				echo print_r($keyword);
				$score=isset($keyword['sentiment']['score'])? $keyword['sentiment']['score'] : 0;
				if(strpos(strtolower($keyword['text']),'iphone')>-1){ 
					$store1=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					if (array_key_exists('score', $keyword['sentiment'])){ 
						$store1->execute(array(
							':name'=>'iPhone',
							':score'=>$keyword['sentiment']['score']
							));
					}
				}elseif (strpos(strtolower($keyword['text']),'galaxy')>-1||strpos(strtolower($keyword['text']),'samsung')>-1||strpos(strtolower($keyword['text']),'s3')>-1||strpos(strtolower($keyword['text']),'s4')>-1||strpos(strtolower($keyword['text']),'note 3')>-1||strpos(strtolower($keyword['text']),'s duos')>-1) {
					$store2=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store2->execute(array(
						':name'=>'Samsung',
						':score'=>$score
						));
				}elseif (strpos(strtolower($keyword['text']),'micromax')>-1||strpos(strtolower($keyword['text']),'canvas')>-1) {
					$store3=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store3->execute(array(
						':name'=>'Micromax',
						':score'=>$score
						));

				}elseif (strpos(strtolower($keyword['text']),'xperia')>-1||strpos(strtolower($keyword['text']),'sony')>-1) {
					$store4=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store4->execute(array(
						':name'=>'Sony',
						':score'=>$score
						));
				}elseif (strpos(strtolower($keyword['text']),'one x')>-1||strpos(strtolower($keyword['text']),'htc')>-1) {
					$store5=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store5->execute(array(
						':name'=>'HTC',
						':score'=>$score
						));
				}elseif (strpos(strtolower($keyword['text']),'nexus')>-1) {
					$store6=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store6->execute(array(
						':name'=>'Nexus',
						':score'=>$score
						));
				}elseif (strpos(strtolower($keyword['text']),'lumia')>-1||strpos(strtolower($keyword['text']),'nokia')>-1||strpos(strtolower($keyword['text']),'asha')>-1) {
					$store6=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store6->execute(array(
						':name'=>'Nokia',
						':score'=>$score
						));
				}elseif (strpos(strtolower($keyword['text']),'lg')>-1) {
					$store7=$db->prepare("INSERT INTO senti (key_name,key_score) VALUES (:name,:score)");
					$store7->execute(array(
						':name'=>'LG',
						':score'=>$score
						));
				}
			} echo(++$i."  ");
		}
	}
		
	

?>