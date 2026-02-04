function showPassword()
{
    var passwords = document.getElementsByClassName("hiddenPassword");
    var button = document.getElementById("showPasswordButton");
    Array.from(passwords).forEach(function(password)
    {
        if(password.type === "password")
        {
            button.innerHTML = 'Hide Password';
            password.type = "text";
        }
        else
        {
            button.innerHTML = 'Show Password';
            password.type = "password";
        }
    });
}