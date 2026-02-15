<?php
require('/srv/phlo/phlo.php');
phlo_app (
	id: 'TeslaCam',
	host: 'teslacam.online',
	app: '/srv/teslacam.online/release/',
	data: '/srv/teslacam.online/data/',
	php: '/srv/teslacam.online/release/',
);
