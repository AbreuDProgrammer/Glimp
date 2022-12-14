<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="sidebar">
    <div class="item-sidebar"><a href="<?= base_url(); ?>"><img id="logo-sidebar" src="<?= base_url('assets/images/glimp-logo-small.png'); ?>"></a></div>
    <div class="item-sidebar"><a href="<?= base_url('search'); ?>"><img class="href-sidebar" src="<?= base_url('assets/images/search.png'); ?>"></a></div>
    <div class="item-sidebar"><a href="<?= base_url('friends'); ?>"><img class="href-sidebar" src="<?= base_url('assets/images/friends.png'); ?>"></a></div>
    <div class="item-sidebar"><a href="<?= base_url($profile); ?>"><img class="href-sidebar" src="<?= base_url('assets/images/profile.png'); ?>"></a></div>
    <div class="item-sidebar"><a href="<?= base_url('logout'); ?>"><img class="href-sidebar" src="<?= base_url('assets/images/logout.png'); ?>"></a></div>
</div>