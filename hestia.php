<?php
/* HestiaCP WHMCS Module 
   https://github.com/JosephChuks/HestiaCP-WHMCS-Module
*/


function hestia_MetaData()
{
    return array(
        'DisplayName' => 'HestiaCP',
        'APIVersion' => '1.1',
        'RequiresServer' => true,
        'DefaultNonSSLPort' => '8083',
        'DefaultSSLPort' => '8083',
        'ServiceSingleSignOnLabel' => 'Login as User',
        'AdminSingleSignOnLabel' => 'Login as Admin'
    );
}



function hestia_ConfigOptions($params)
{
    return [
        'Package Name' => [
            'Type' => 'text',
            'Default' => 'default'
        ],
        'SSH Access' => [
            'Type' => 'yesno',
            'Description' => 'Tick to grant access',
            'Default' => 'no'
        ],
        'Server IP Address' => [
            'Type' => 'text',
        ],
    ];
}


function hestia_AdminCustomButtonArray()
{
    return array(
        "Install LetsEncrypt SSL" => "InstallSsl",
    );
}


function hestia_CreateAccount($params)
{


    if ($params["server"] == 1) {


        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-add-user',
            'arg1' => $params["username"],
            'arg2' => $params["password"],
            'arg3' => $params["clientsdetails"]["email"],
            'arg4' => $params["configoption1"],
            'arg5' => $params["clientsdetails"]["firstname"],
            'arg6' => $params["clientsdetails"]["lastname"],
        );
        $postdata = http_build_query($postvars);


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);

        logModuleCall('hestia', 'CreateAccount_UserAccount', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

        // Enable ssh access
        if (($answer == 0) && ($params["configoption2"] == 'on')) {
            $postvars = array(
                'hash' => $params["serveraccesshash"],
                'returncode' => 'yes',
                'cmd' => 'v-change-user-shell',
                'arg1' => $params["username"],
                'arg2' => 'bash'
            );
            $postdata = http_build_query($postvars);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $answer = curl_exec($curl);

            logModuleCall('hestia', 'CreateAccount_EnableSSH', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);
        }

        // Add domain
        if (($answer == 0) && (!empty($params["domain"]))) {
            $postvars = array(
                'hash' => $params["serveraccesshash"],
                'returncode' => 'yes',
                'cmd' => 'v-add-domain',
                'arg1' => $params["username"],
                'arg2' => $params["domain"],
                'arg3' => $params["configoption3"],
            );
            $postdata = http_build_query($postvars);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $answer = curl_exec($curl);

            logModuleCall('hestia', 'CreateAccount_AddDomain', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);
        }
    }

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestia_TerminateAccount($params)
{


    if ($params["server"] == 1) {


        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-delete-user',
            'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Delete user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestia', 'TerminateAccount', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestia_SuspendAccount($params)
{


    if ($params["server"] == 1) {


        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-suspend-user',
            'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Susupend user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestia', 'SuspendAccount', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestia_UnsuspendAccount($params)
{


    if ($params["server"] == 1) {


        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-unsuspend-user',
            'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Unsusupend user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestia', 'UnsuspendAccount', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestia_ChangePassword($params)
{


    if ($params["server"] == 1) {


        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-change-user-password',
            'arg1' => $params["username"],
            'arg2' => $params["password"]
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestia', 'ChangePassword', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestia_ChangePackage($params)
{


    if ($params["server"] == 1) {


        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-change-user-package',
            'arg1' => $params["username"],
            'arg2' => $params["configoption1"]
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestia', 'ChangePackage', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}



function hestia_InstallSsl($params)
{


    if ($params["server"] == 1) {

        $postvars = array(
            'hash' => $params["serveraccesshash"],
            'returncode' => 'yes',
            'cmd' => 'v-add-letsencrypt-domain',
            'arg1' => $params["username"],
            'arg2' => $params["domain"],
            'arg3' => '',
            'arg4' => 'yes',
        );
        $postdata = http_build_query($postvars);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestia', 'InstallSSL', 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/' . $postdata, $answer);

    if ($answer == 0) {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestia_ClientArea($params)
{

    $code = '
<form action="https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/login/" method="post" target="_blank">
<input type="hidden" name="user" value="' . $params["username"] . '" />
<input type="hidden" name="password" value="' . $params["password"] . '" />
<input type="hidden" name="api" value="1" />
<input type="submit" value="Login to Control Panel" />
</form>';
    return $code;
}

function hestia_AdminLink($params)
{

    $code = '<form action="https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/login/" method="post" target="_blank">
<input type="hidden" name="user" value="' . $params["serverusername"] . '" />
<input type="hidden" name="password" value="' . $params["serverpassword"] . '" />
<input type="submit" value="Login to Control Panel" />
</form>';
    return $code;
}

function hestia_LoginLink($params)
{

    echo '
    <style>#btnLoginLinkTrigger { display: none }</style>
        <div class="col-sm-5">
        <a href="https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/login/" class="btn btn-primary" target="_blank">
            <i class="fas fa-sign-in fa-fw"></i> Login to Control Panel
        </a>
    </div>
    ';
}


function hestia_UsageUpdate($params)
{

    $postvars = array(
        'hash' => $params["serveraccesshash"],
        'returncode' => 'yes',
        'cmd' => 'v-list-users',
        'arg1' => 'json'
    );
    $postdata = http_build_query($postvars);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':' . $params["serverport"] . '/api/');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);

    $results = json_decode($answer, true);


    foreach ($results as $user => $values) {
        update_query("tblhosting", array(
            "diskusage" => $values['U_DISK'],
            "disklimit" => $values['DISK_QUOTA'],
            "bwusage" => $values['U_BANDWIDTH'],
            "bwlimit" => $values['BANDWIDTH'],
            "lastupdate" => "now()",
        ), array("server" => $params['serverid'], "username" => $user));
    }
}
