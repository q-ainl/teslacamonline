<?php
class visitors extends model {
	public static $table = 'visitors';
	public static $columns = 'id,token,host,page,lang,IP,browser,os,device,requests,state,width,height,referrer,created,changed';
	public static function history(){
		return static::records(columns: 'FROM_UNIXTIME(changed, "%Y-%m-%d") AS date,COUNT(DISTINCT token) AS visitors,COUNT(id) AS visits', group: 'date', order: 'date DESC');
	}
	public static function online(){
		return static::item(columns: 'COUNT(DISTINCT token)', where: 'changed >= (UNIX_TIMESTAMP() - 9)');
	}
	public static function lastHour(){
		return static::item(columns: 'COUNT(DISTINCT token)', where: 'changed >= (UNIX_TIMESTAMP() - 3600)');
	}
	public static function PUTHeartbeatNVLUWHAPR(){
		$id = token(20, (strlen(phlo('payload')->n) === 8 ? phlo('payload')->n : date('Ymd')).space.phlo('app')->token.space.phlo('useragent')->source);
		$data = arr (
			token: phlo('app')->token ?? error('No app token available'),
			host: $_SERVER['HTTP_HOST'],
			page: phlo('payload')->u,
			lang: strlen(phlo('payload')->l) === 2 ? phlo('payload')->l : phlo('app')->lang ?? 'en',
			IP: $_SERVER['REMOTE_ADDR'],
			browser: phlo('useragent')->full.(phlo('payload')->a ? ' App' : void),
			os: phlo('useragent')->osFull,
			device: phlo('useragent')->device,
			requests: phlo('payload')->p,
			state: phlo('payload')->v,
			width: phlo('payload')->w,
			height: phlo('payload')->h,
			changed: time(),
		);
		$host = apcu_fetch('watched');
		$record = static::record(id: $id, columns: 'id,page,referrer');
		if (($referrer = phlo('payload')->r) && !$record?->referrer && !strpos($referrer, host)) $data['referrer'] = $referrer;
		if ($record) [static::change('id=?', $id, ...$data), $host && $record->page !== phlo('payload')->u && wsCast(wsHost: $host, toast: 'Request: '.$_SERVER['REMOTE_ADDR'].' - '.$_SERVER['HTTP_HOST'].slash.phlo('payload')->u)];
		else static::create(...$data, id: $id, created: time()) && $host && wsCast(wsHost: $host, toast: 'Visitor: '.$_SERVER['REMOTE_ADDR'].' - '.$_SERVER['HTTP_HOST'].slash.phlo('payload')->u);
	}
}
