<?php
$database = 'db.json';
$username = $_GET['username'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (empty($username)) {
  http_response_code(403);
  exit();
}

if (!file_exists($database)) {
    file_put_contents($database, '{}');
}

$db = json_decode(file_get_contents($database), true);

if (!isset($db[$username])) {
  $db[$username] = [
    'count' => 1,
    'ip' => $ip,
    'ua' => $user_agent,
  ];
} else {
  $db[$username]['count']++;
  $db[$username]['ip'] = $ip;
  $db[$username]['ua'] = $user_agent;
}

file_put_contents($database, json_encode($db));

$count = $db[$username]['count'];

header('Content-Type: image/svg+xml');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="106" height="20">
    <linearGradient id="b" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <mask id="a">
        <rect width="106" height="20" rx="3" fill="#fff"/>
    </mask>
    <g mask="url(#a)">
        <rect width="81" height="20" fill="#555"/>
        <rect x="81" width="25" height="20" fill="#0e75b6"/>
        <rect width="106" height="20" fill="url(#b)"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
        <text x="41.5" y="15" fill="#010101" fill-opacity=".3">Profile views</text>
        <text x="41.5" y="14">Profile views</text>
        <text x="92.5" y="15" fill="#010101" fill-opacity=".3">$count</text>
        <text x="92.5" y="14">$count</text>
    </g>
</svg>
SVG;
?> 