<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 id="page_title">Account Settings</h1>
<form method="post" id="user_form">
    <input type="hidden" value="<?= $user_id; ?>" name="user_id">
    <div class="form_item">
        <h2>Username</h2>
        <input value="<?= $username; ?>" type="text" name="username" placeholder="Username">
    </div>
    <div class="form_item">
        <h2>Email</h2>
        <input value="<?= $email; ?>" type="email" name="email" placeholder="Email">
    </div>
    <div class="form_item">
        <h2>Phone number</h2>
        <input value="<?= $phone; ?>" type="tel" name="phone" placeholder="Phone">
    </div>
    <div class="form_item">
        <h2>Name</h2>
        <input value="<?= $name; ?>" type="text" name="name" placeholder="Name">
    </div>
    <div class="form_item">
        <h2>Birthday</h2>
        <input value="<?= $birthday; ?>" type="date" name="birthday" placeholder="Birthday">
    </div>
    <input type="submit" value="Submit">
</form>
<div id="form_infos">
    <?= $error['form_info']; ?>
</div>