var MSG = {
    Error: function (text) {
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            html: text,
        })
    },
    Warning: function (text) {
        Swal.fire({
            type: 'warning',
            title: 'Oops...',
            html: text,
        })
    },
    Permission_Deny: function () {
        Swal.fire({
            type: 'error',
            title: 'Permission Denied...',
            html: "User have no permission!!!",
        })
    },
    Validation: function (text) {

        Swal.fire({
            title: 'Required',
            type: 'info',
            html: text,
            closeModal: false
        })

    },
    Success: function () {
        Swal.fire({
            position: 'top-center',
            type: 'success',
            title: 'Save Successful!',
            showConfirmButton: false,
            timer: 1200
        })
    },
    Loading: function () {
        Swal.fire({
            title: '<i>Loading</u>',
            type: 'info',
            html: 'Please wait data is loading...',
        })
    }
}

var Input = {
    IsNumber: function (e, t) {

        if (t.value.indexOf(".") == 0) t.value = "0.";
        var dot = t.value.indexOf(".");


        var unicode = e.charCode ? e.charCode : e.keyCode;

        var ctrlDown = e.ctrlKey || e.metaKey;

        if (ctrlDown == true) {

            if (unicode == 99 || unicode == 118 || unicode == 120) {
                return true;
            }

        }

        if (unicode == 37 || unicode == 39 || unicode == 43 || unicode == 45) return true;

        if (unicode != 8 && unicode != 46 && unicode != 9) {
            if (unicode < 48 || unicode > 57)
                return false;
        }
        if (unicode == 46 && dot > 0) return false;
    },
}
