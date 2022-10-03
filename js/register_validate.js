var valName = 1;
var valEmail = 1;
var valUser = 1;
var valMob = 1;
var valPassRegister = 1;
var errorText = '{"font-size":"12px","color":"#a94442","display":"inline-block","padding": "5px","font-weight": "700"}';
errorText = JSON.parse(errorText);
var errorInput = '{"outline":"none","border-color":"#a94442"}';
errorInput = JSON.parse(errorInput);
var removeError = '{"outline":"none","border-color":"#ccc"}';
removeError = JSON.parse(removeError);

function showError(key, value)
{
    key = "#"+key;
    var selector = "input"+key;
    $(selector).prev("span").remove();
    $(key).css(errorInput);
    var txt = $("<span></span>").text(value).css(errorText);
    $(key).before(txt);
}

function validateEmail(val)
{
    var re = /^\S+@\w+\.\w+$/;
    return re.test(val);
}

function name()
{
    var name = $("#name").val();
    $("#name + span").text("");
    if(name === "")
    {
        valName = 1;
        showError("name", " *Porfavor ingresa tu nombre");
    }
    else
    {
        $("#name").css(removeError);
        valName = 0;
    }
}

function email()
{
    var val = $("#email").val();
    var ret = validateEmail(val);
    $("input#email").prev("span").remove();
    if(val === "")
    {
        valEmail = 1;
        showError("email", " *Ingresa tu correo Electronico");
    }
    else if(!ret)
    {
        valEmail = 1;
        showError("email", " *Invalid Email");
    }
    else
    {
        $("#email").css(removeError);
        valEmail = 0;
    }
}

function username()
{
    var val = $("#username").val();
    var re = /^\S+@/;

    $("input#username").prev("span").remove();
    if(val === "")
    {
        valUser = 1;
        showError("username", " *Introduce tu nombre de ususario");
    }
    else if(re.test(val))
    {
        valUser = 1;
        showError("username", " *Nombre de Usuario Invalido");
    }
    else
    {
        $("#username").css(removeError);
        valUser = 0;
    }
}

function mob()
{
    var mob = $("#mob").val();
    var re = /^[0-9]{10}$/;
    $("input#mob").prev("span").remove();
    if(mob === "")
    {
        valMob = 1;
        showError("mob", " *Ingresa tu numero telefonico");
    }
    else if(!re.test(mob))
    {
        valMob = 1;
        showError("mob", " *Enter 10 digit mobile no.");
    }
    else
    {
        $("#mob").css(removeError);
        valMob = 0;
    }
}

function passwordRegister()
{
    var pass = $("#passRegister").val();
    $("input#passRegister").prev("span").remove();
    if(pass === "")
    {
        valPassRegister = 1;
        showError("passRegister", " *Introduce tu contraseña");
    }
    else
    {
        $("#passRegister").css(removeError);
        valPassRegister = 0;
    }
}

function initRegister()
{
    name();
    email();
    username();
    mob();
    passwordRegister();
}

// Name validation

$("#name").blur(function()
{
    name();
});

// Email validation

$("#email").keyup(function() {
    email();
});

$("#email").blur(function() {
    email();
});


// username validation

$("#username").blur(function() {
    username();
});

// Mobile validation

$("#mob").blur(function() {
    mob();
});

//Password validation

$("#passRegister").blur(function() {
    passwordRegister();

});

function registerCheck() {
    var name = $("#name").val();
    var email = $("#email").val();
    var username = $("#username").val();
    var mob = $("#mob").val();
    var password = $("#passRegister").val();

    initRegister();

    if(valName === 0 && valEmail === 0 && valUser === 0 && valMob === 0 && valPassRegister === 0)
    {
        var q = {
            "name": name,
            "email": email,
            "username": username,
            "mob": mob,
            "password": password
        };
        q = "q=" + JSON.stringify(q);
          console.log(q);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var result = JSON.parse(xmlhttp.responseText);
                 console.log("el resultado es: "+result);
                 console.log(xmlhttp.responseText);
                if(result["location"])
                {
                    location.href = result["location"];
                }
                $(result).each(function(index, element) {
                    showError(element["key"], element["value"]);
                });
            }
        };
        xmlhttp.open("POST", "ajax/validate_register.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(q);
    }
    else
    {
        // alert("Please Fill correct details");
        $("#myModal").modal();
    }
}

