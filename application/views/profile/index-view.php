<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="profile">
    <div id="picture_div">
        <img id="profile_img" src="https://oghma.epcc.pt/system/users/avatars/000/004/342/original/blob?1530525716">
    </div>
    <div id="info">        
        <div id="username">
            <h1><?= $username; ?></h1>
            <? if($is_owner): ?>
            <a href="<?= base_url($profile.'/account'); ?>"><img id="engrenagem" src="https://cdn-icons-png.flaticon.com/512/1160/1160356.png"></a>
            <? endif; ?>
        </div>
        <div id="name">
            <h2><?= $name??NULL; ?></h2>
        </div>
    </div>
</div>