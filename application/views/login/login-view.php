<div id="loginDiv">
    <div id="tittleDiv">
        <h1>Login</h1>
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
            <div class="submitDiv">
                <input type="submit">
            </div>
        </form>
    </div>
    <div id="createAccountLinkDiv">
        <p>Doesn't have an account? <a href="<?= $link['createAccountLink']; ?>">Create one!</a></p>
    </div>
</div>