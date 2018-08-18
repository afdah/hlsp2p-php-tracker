<?php
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'On');

$route = $_GET['command'];
$input = file_get_contents("php://input");
$post_data = json_decode($input, true);

switch($route){
	case'channel':
		$channel = $post_data['channel'];
		$device = $post_data['device'];
		$netType = $post_data['device'];
		$host = $post_data['host'];
		$version = $post_data['version'];
		$tag = $post_data['tag'];
		$peer_id = uniqid();
		
		$roomDir = 'peers/'.$channel;
		makeDir($roomDir);
		insertPeer(
			$roomDir,
			array(
				"id"=>$peer_id,
				"device"=>$device,
				"netType"=>$netType,
				"host"=>$host,
				"version"=>$version,
				"tag"=>$tag,
				"conns"=>0,
				"http"=>0,
				"p2p"=>0,
				"failConns"=>0
			)
		);
		$out = array(
			'ret'=>0,
			'name'=>'channel',
			'data'=>array(
					'id'=>$peer_id,
					'report_interval'=>10,
					'peers'=>get_peers($roomDir,$peer_id)
			)
		);
		echo json_encode($out);
		break;
	case'get_peers':
		$info_hash = md5($_GET['info_hash']);
		$peer_id = $_GET['peer_id'];
		$roomDir = 'peers/'.hashDir($info_hash);
		$out = array(
			'peers'=>get_peers($roomDir,$peer_id),
		);
		echo json_encode($out);
		break;
	case'heartbeat':
		$info_hash = md5($_GET['info_hash']);
		$peer_id = $_GET['peer_id'];
		$roomDir = '/peers/'.hashDir($info_hash);
		touch(__DIR__ .$roomDir.$peer_id);
		break;
}
function insertPeer($room,$info){
	file_put_contents($room.'/'.$info['id'],json_encode($info));
}
function get_peers($room,$exclude=''){
	$arr = glob(__DIR__ . '/' .$room . '/*');
	if($arr){
		$out = array();
		$expTime = time()-30;
		foreach($arr as $v){
			if(filemtime($v)>$expTime){
				if($v!=__DIR__ . '/' .$room . '/' . $exclude){
					$json = json_decode(file_get_contents($v));
					$out[] = (object)array('id'=>$json->{'id'});
				}
			}else{
				unlink($v);
			}
		}
		return $out;
	}else{
		return array();
	}
}
function makeDir($path){
	if(!is_dir($path)){
		$str = dirname($path);
		if($str){
			makeDir($str.'/');
			@mkdir($path,0777);
			chmod($path,0777);
		}
	}
}
?>
