<?php

// Forked from https://gist.github.com/1809044
// Available from https://gist.github.com/nichtich/5290675#file-deploy-php

$TITLE   = 'Git Deployment Hamster';
$VERSION = '0.11';

echo <<<EOT
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>$TITLE</title>
</head>
<body style="background-color: #000000; color: #FFFFFF; font-weight: bold; padding: 0 10px;">
<pre>
  o-o    $TITLE
 /\\"/\   v$VERSION
(`=*=')
 ^---^`-.


EOT;

// Check whether client is allowed to trigger an update

$allowed_ips = array(
	'207.97.227.', '50.57.128.', '108.171.174.', '50.57.231.', '204.232.175.', '192.30.252.', // GitHub
	'195.37.139.','193.174.' // VZG
);
$allowed = false;

$headers = apache_request_headers();

if (@$headers["X-Forwarded-For"]) {
    $ips = explode(",",$headers["X-Forwarded-For"]);
    $ip  = $ips[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

foreach ($allowed_ips as $allow) {
    if (stripos($ip, $allow) !== false) {
        $allowed = true;
        break;
    }
}
$allowed = true;

if (!$allowed) {
	header('HTTP/1.1 403 Forbidden');
 	echo "<span style=\"color: #ff0000\">Sorry, no hamster - better convince your parents!</span>\n";
    echo "</pre>\n</body>\n</html>";
    exit;
}

flush();

// Actually run the update

$commands = array(




	'echo $PWD',
    'cd /home/u143685859/domains/oidea.online/public_html/pickndeal/laravel_app && /usr/bin/php /usr/local/bin/composer2 install && cd public',

	'whoami',
    'git remote -v',
    'ssh-keyscan -t rsa github.com >> ~/.ssh/known_hosts',
    'git stash push --include-untracked',
	'git pull',
	'git status',
    //'nvm use 16 && cd  /home/idea99/projects/pickndeal/admin_vue && npm install && npm run build && cp -rf /home/idea99/projects/pickndeal/admin_vue/dist/* /home/admin/web/dadmin.oidea.xyz/public_html/',

	'git submodule sync',
	'git submodule update',
	'git submodule status',

    'php /home/u143685859/domains/oidea.online/public_html/pickndeal/laravel_app/artisan migrate',
    'php /home/u143685859/domains/oidea.online/public_html/pickndeal/laravel_app/artisan db:seed',

    //'test -e /usr/share/update-notifier/notify-reboot-required && echo "system restart required"',
);

$output = "\n";

$log = "####### ".date('Y-m-d H:i:s'). " #######\n";

foreach($commands AS $command){
    // Run it
    $tmp = shell_exec("$command 2>&1");
    // Output
    $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
    $output .= htmlentities(trim($tmp)) . "\n";

    $log  .= "\$ $command\n".trim($tmp)."\n";
}

$log .= "\n";

file_put_contents ('deploy-log.txt',$log,FILE_APPEND);

echo $output;


?>
</pre>
</body>
</html>
