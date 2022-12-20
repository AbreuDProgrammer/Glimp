<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 id="page_title">Account Permissions Settings</h1>
<form method="post" id="user_form">
    <input type="hidden" value="<?= $user_id_data_permissions; ?>" name="user_id_data_permissions">
    <h2>Who can see your data:</h2>

    <div class="form_item">
        <h3>Email</h3>

        <input type="radio" id="email_public" name="email" value="public" <? if($email == 'public') echo 'checked'; ?>>
        <label for="email_public">Everyone</label>

        <input type="radio" id="email_protected" name="email" value="protected" <? if($email == 'protected') echo 'checked'; ?>>
        <label for="email_protected">Just friends</label>

        <input type="radio" id="email_private" name="email" value="private" <? if($email == 'private') echo 'checked'; ?>>
        <label for="email_private">No one</label>
    </div>
    <div class="form_item">
        <h3>Phone number</h3>

        <input type="radio" id="phone_public" name="phone" value="public" <? if($phone == 'public') echo 'checked'; ?>>
        <label for="phone_public">Everyone</label>

        <input type="radio" id="phone_protected" name="phone" value="protected" <? if($phone == 'protected') echo 'checked'; ?>>
        <label for="phone_protected">Just friends</label>

        <input type="radio" id="phone_private" name="phone" value="private" <? if($phone == 'private') echo 'checked'; ?>>
        <label for="phone_private">No one</label>
    </div>
    <div class="form_item">
        <h3>Name</h3>

        <input type="radio" id="name_public" name="name" value="public" <? if($name == 'public') echo 'checked'; ?>>
        <label for="name_public">Everyone</label>

        <input type="radio" id="name_protected" name="name" value="protected" <? if($name == 'protected') echo 'checked'; ?>>
        <label for="name_protected">Just friends</label>

        <input type="radio" id="name_private" name="name" value="private" <? if($name == 'private') echo 'checked'; ?>>
        <label for="name_private">No one</label>
    </div>
    <div class="form_item">
        <h3>Birthday</h3>

        <input type="radio" id="birthday_public" name="birthday" value="public" <? if($birthday == 'public') echo 'checked'; ?>>
        <label for="birthday_public">Everyone</label>

        <input type="radio" id="birthday_protected" name="birthday" value="protected" <? if($birthday == 'protected') echo 'checked'; ?>>
        <label for="birthday_protected">Just friends</label>

        <input type="radio" id="birthday_private" name="birthday" value="private" <? if($birthday == 'private') echo 'checked'; ?>>
        <label for="birthday_private">No one</label>
    </div>
    <input type="submit" value="Submit">
</form>
<div id="form_infos">
    <?= $error['form_info']; ?>
</div>
<a href="<?= base_url($username.'/account'); ?>">Go back</a>