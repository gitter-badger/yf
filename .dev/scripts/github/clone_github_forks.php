#!/usr/bin/php
<?php

require __DIR__.'/github_api_funcs.php';

$user = 'yfix';
include __DIR__.'/data/'.$user.'_repos.php';

$d = '/home/www/github_forks/';
!file_exists($d) && mkdir($d, 1);

function get_repo_info($user, $name) {
	include __DIR__.'/data/'.$user.'/'.$name.'.php';
	return $data;
}

# https://help.github.com/articles/syncing-a-fork
foreach ($data as $k => $a) {
	if (!$a['fork']) {
		continue;
	}
	echo PHP_EOL.'('.($k+1).'/'.count($data).') == '.$a['full_name'].' =='.PHP_EOL.PHP_EOL;
	$target = $d.$a['name'];
	if (file_exists($target.'/.git/config')) {
		passthru('(cd '.$target.' && git pull -r)');
	} else {
		$clone_url = 'git@github.com:yfix/'.$a['name'].'.git';
		passthru('git clone '.$clone_url.' '.$target);
	}

	$repo_info = get_repo_info($user, $a['name']);
	$source_repo = $repo_info['source']['full_name'];
	$upstream = 'https://github.com/'.$source_repo.'.git';

	echo $source_repo. PHP_EOL;

	$out = null;
	exec('(cd '.$target.' && git remote -v)', $out);
	$out = implode(PHP_EOL, $out);

	echo PHP_EOL. $out. PHP_EOL;

	if (false === strpos($out, $upstream)) {
		passthru('(cd '.$target.' && git remote add upstream '.$upstream.')');
	}
	passthru('(cd '.$target.' && git fetch upstream)');
	passthru('(cd '.$target.' && git checkout master)');
	passthru('(cd '.$target.' && git reset --hard upstream/master)');
	passthru('(cd '.$target.' && git merge upstream/master)');
	passthru('(cd '.$target.' && git push --all)');
}