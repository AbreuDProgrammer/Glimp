<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<a href="<?= base_url($profile.'/account'); ?>">Account</a>
<div id="profile">
    <div id="picture_div">
        <img id="profile_img" src="https://oghma.epcc.pt/system/users/avatars/000/004/342/original/blob?1530525716">
    </div>
    <div id="info">        
        <div id="username">
            <h1><?= $username; ?></h1>
        </div>
        <div id="name">
            <h2><?= $name??NULL; ?></h2>
        </div>
    </div>
</div>