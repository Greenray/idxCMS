/*
 * idxCMS Flat Files Content Management System v4.1
 * Copyright (c) 2011-2016 Victor Nabatov greenray.spb@gmail.com
 */

function checkPassword(id) {
    var form = document.getElementById(id);
    if (form.password.value === form.confirm.value) {
        if (form.password.value !== '') {
            document.getElementById('yes').style.display = 'block';
            document.getElementById('no').style.display  = 'none';
        }
    } else {
        document.getElementById('yes').style.display = 'none';
        document.getElementById('no').style.display  = 'block';
    }
}
function getPasswordStrength(pw) {
    var pwlength = (pw.length);
    if (pwlength > 5) pwlength = 5;
    var numnumeric = pw.replace(/[0-9]/g, '');
    var numeric = (pw.length - numnumeric.length);
    if (numeric > 3) numeric = 3;
    var symbols = pw.replace(/\W/g, '');
    var numsymbols = (pw.length - symbols.length);
    if (numsymbols > 3) numsymbols = 3;
    var numupper = pw.replace(/[A-Z]/g, '');
    var upper = (pw.length - numupper.length);
    if (upper > 3) upper = 3;
    var pwstrength = ((pwlength * 10) - 20) + (numeric * 10) + (numsymbols * 15) + (upper * 10);
    if (pwstrength < 0)   pwstrength = 0;
    if (pwstrength > 100) pwstrength = 100;
    return pwstrength;
}
function updatePasswordStrength(pwbox, pwdiv, divorderlist) {
    var bpb  = "" + pwbox.value;
    var pwstrength = getPasswordStrength(bpb);
    var bars = (parseInt(pwstrength / 10) * 10);
    var pwdivEl = document.getElementById(pwdiv);
    if (!pwdivEl) alert('Password strength display element missing');
    var divlist = pwdivEl.getElementsByTagName('span');
    var imgdivnum = 0;
    if (divorderlist && divorderlist.image > -1) imgdivnum = divorderlist.image;
    var imgdiv = divlist[imgdivnum];
    imgdiv.id  = 'passbar-' + bars;
}
