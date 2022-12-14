<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="profile-page">
    <a href="<?= base_url($profile.'/account'); ?>">Account</a>
    <div id="username">
        <h1><?= $username; ?></h1>
    </div>
    <div id="email">
        <h2><?= $email; ?></h2>
    </div>
</div>