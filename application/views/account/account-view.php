<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="account-page">
    <form method="post">
        <input type="hidden" value="<?= $id; ?>" name="user_id">
        <input value="<?= $username; ?>" type="text" name="username" placeholder="Username">
        <input value="<?= $email; ?>" type="email" name="email" placeholder="Email">
        <input value="" type="tel" name="phone" placeholder="Phone">
        <input value="" type="text" name="name" placeholder="Name">
        <input value="" type="date" name="birthday" placeholder="Birthday">
        <input type="submit">
    </form>
</div>