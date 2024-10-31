<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

function http_test($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret !== false;
}
function wx_test($url)
{
    $url = 'https://mp.weixinbridge.com/mp/wapredirect?url='.$url;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $ret = curl_exec($ch);
    $redirect = false;
    if (!curl_errno($ch)) {
        $info = curl_getinfo($ch);
        if($info['http_code']==302 && strpos($info['redirect_url'],'https://weixin110.qq.com')===false){
            $redirect = true;
        }
    }
    curl_close($ch);
    if($ret!==false && !$redirect){
        return false;
    }
    return true;
}

$host = $_REQUEST['host'];
//$host = $_SERVER['argv'][1];

if (empty($host)) {
    echo json_encode(['code' => 400]);
    exit();
}

$rsp = http_test($host);
if (!$rsp) {
    echo json_encode(['code' => 100]);
    exit();
}
$rsp = wx_test($host);
if ($rsp) {
    echo json_encode(['code' => 200]);
    exit();
}
echo json_encode(['code' => 100]);
