<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="nav-holder"> 
    <div id="nav">
        <div><a href="<?= base_url(); ?>">Home</a></div>
        <div><a href="<?= base_url('search'); ?>">Search</a></div>
        <div><a href="<?= base_url('friends'); ?>">Friends</a></div>
        <div><a href="<?= base_url($profile); ?>">Profile</a></div>
        <div><a href="<?= base_url('logout'); ?>">Log out</a></div>
    </div>
</div>