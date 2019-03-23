// this is where we will put most of the JS
function validateEmail(email){
    if (typeof(email) === 'string'){
        // email validations
        //var emailRegExp = new RegExp('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/');
        var emailRegExp = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
        if (emailRegExp.test(email) == false) {
            return false;
        } else {
            return true;
        }
    }
    return false;
}

function validatePassword(passwd) {
    // password validations
    if (typeof(passwd) === 'string'){
        var passwdRegExp = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
        if(passwdRegExp.test(passwd) == false){
            //alert('Password [6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter]');
            return false;
        }
    } else return false;
    return true;
}

function validateUsername(username) {
    if (typeof(username) === "string") {
        var usernameRegExp = /([A-Za-z_0-9]+){4,}/;
        return usernameRegExp.test(username);
    } else return false;
    return true;
}

function validateName(name) {
    var nameRegExp = //;
    return nameRegExp.test(name);
}

function validateAadhar(aadhar) {
    var aadharRegExp = /[0-9]{12}/;
    return aadharRegExp.test(aadhar) ;
}

function validateContact(contact) {
    var contactRegExp = /([+]?)[0-9]{0,20}/;
    return contactRegExp.test(contact);
}

function validateSrcDestForm() {
    if($('#source').val() !== $("#destination").val()) {
        // alert("Source and destination are not same");
        return true;
    } else {
        alert("Source and destination can't be same");
        return false;
    }
}