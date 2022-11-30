<div id="createAccountDiv">
    <div id="tittleDiv">
        <h1>Create Account</h1>
    </div>
    <div id="formDiv">
        <form method="post">
            <div class="inputDiv">
                <label class="formLabels" id="usernameLabel" for="username">Username:</label>
                <input type="text" id="usernameInput" name="username">
            </div>
            <div class="inputDiv">
                <label class="formLabels" id="passwordLabel" for="password">Password:</label>
                <input type="password" id="passwordInput" name="password">
            </div>
            <div class="inputDiv">
                <label class="formLabels" id="passwordLabel" for="password_repeated">Repeate the password:</label>
                <input type="password" id="passwordInput" name="password_repeated">
            </div>
            <div class="submitDiv">
                <input type="submit">
            </div>
        </form>
    </div>
    <div id="loginLinkDiv">
        <p>Have an account? <a href="<?= $link['loginLink']; ?>">Log in!</a></p>
    </div>
</div>