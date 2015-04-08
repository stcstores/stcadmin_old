function addError(errorText) {
        errorCount ++;
        errors.append('<p>' + errorText + '</p>');
    }

function login() {
    var illegalchars = ['<', '>', ';', '"', "'"];
    var username = $('#login_username').val().toString();
    var password = $('#login_password').val().toString();
    errors = $('#login_errors');
    errorCount = 0;
    if (username.length < 1) {
        addError('Please input Username');
    } else if (username.length < 5) {
        addError('Username must contain at least 5 characters');
    }
    
    if (password.lenght < 1) {
        addError('Please input Password');
    } else if (username.length < 8) {
        addError('Password must contain at least 8 characters');
    }
    
    for (var i=0; i < illegalchars.length; i++) {
        if (!(username.indexOf(illegalchars[i]) === -1)) {
            errors.append('<p>Illegal character in username: ' + illegalchars[i] + '</p>')
            errorCount ++;
        } else if (!(password.indexOf(illegalchars[i]) === -1)) {
            errors.append('<p>Illegal character in password: ' + illegalchars[i] + '</p>')
            errorCount ++;
        }
    }
    
    if (errorCount === 0) {
        
    }
}

$('document').ready(function () {
    $('#login_button').click(function() {
        login();
    });
});