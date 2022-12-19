<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 id="page_title">Account Permissions Settings</h1>
<form method="post" id="user_form">
    <input type="hidden" value="<?= $user_id; ?>" name="user_id">
    <h2>Who can see your data:</h2>

    <div class="form_item">
        <h3>Username</h3>

        <input type="radio" id="username_public" name="username" value="public" checked>
        <label for="username_public">Everyone</label>

        <input type="radio" id="username_protected" name="username" value="protected">
        <label for="username_protected">Just friends</label>

        <input type="radio" id="username_private" name="username" value="private">
        <label for="username_private">No one</label>
    </div>

    <div class="form_item">
        <h3>Email</h3>

        <input type="radio" id="email_public" name="email" value="public">
        <label for="email_public">Everyone</label>

        <input type="radio" id="email_protected" name="email" value="protected">
        <label for="email_protected">Just friends</label>

        <input type="radio" id="email_private" name="email" value="private">
        <label for="email_private">No one</label>
    </div>
    <div class="form_item">
        <h3>Phone number</h3>

        <input type="radio" id="phone_public" name="phone" value="public">
        <label for="phone_public">Everyone</label>

        <input type="radio" id="phone_protected" name="phone" value="protected">
        <label for="phone_protected">Just friends</label>

        <input type="radio" id="phone_private" name="phone" value="private">
        <label for="phone_private">No one</label>
    </div>
    <div class="form_item">
        <h3>Name</h3>

        <input type="radio" id="name_public" name="name" value="public">
        <label for="name_public">Everyone</label>

        <input type="radio" id="name_protected" name="name" value="protected">
        <label for="name_protected">Just friends</label>

        <input type="radio" id="name_private" name="name" value="private">
        <label for="name_private">No one</label>
    </div>
    <div class="form_item">
        <h3>Birthday</h3>

        <input type="radio" id="birthday_public" name="birthday" value="public">
        <label for="birthday_public">Everyone</label>

        <input type="radio" id="birthday_protected" name="birthday" value="protected">
        <label for="birthday_protected">Just friends</label>

        <input type="radio" id="birthday_private" name="birthday" value="private">
        <label for="birthday_private">No one</label>
    </div>
    <input type="submit" value="Submit">
</form>
<div id="form_infos">
    <?= $error['form_info']; ?>
</div>
<a href="<?= base_url($username.'/account'); ?>">Go back</a>