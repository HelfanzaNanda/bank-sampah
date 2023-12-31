
async function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#preview-image').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);

        const formData = new FormData();
        const file = input.files[0];
        formData.append('file', file);
        const url = `${APP_URL}/api/files/upload`;


        const response = await uploadFiles(url, formData);

        if (response.status) {
            $('#input-filecode').val(response.data.filecode);
        }
    }
}

const request = async (url, method, params = {}) => {

    $('[id^=input]').removeClass("is-invalid");
    $('[id^=input]').next("div").remove();


    const token = getCookie("token");
    let headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
    };
    if (token) {
        headers["Authorization"] = `Bearer ${token}`;
    }

    const options = {
        method: method,
        headers: headers,
    }
    if (method !== "GET") {
        options["body"] = JSON.stringify(params)
    }

    const api = await fetch(url, options);

    console.log('api : ', api);

    const response = await api.json();

    if (api.status == 422 && response.validations) {
        const validations = response.validations
        Object.keys(validations).map(field => {
            $(`#input-${field}`).addClass("is-invalid");
            $(`#input-${field}`).after(`<div class="invalid-feedback">${validations[field][0]}</div>`);
        });
        throw new Error(JSON.stringify(response.validations));
    }

    if (api.status !== 200) {
        showFailedAlert(response.message);
        throw new Error(response.message);
    }

    console.log('response : ', response);

    return response;
}

const uploadFiles = async (url, files = {}) => {

    // $('[id^=input]').removeClass("is-invalid");
    // $('[id^=input]').next("div").remove();


    const token = getCookie("token");
    let headers = {
        // 'Accept': 'application/json',
        // 'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
    };
    if (token) {
        headers["Authorization"] = `Bearer ${token}`;
    }

    const options = {
        method: "POST",
        headers: headers,
        body: files
    }
    // if (method !== "GET") {
    //     options["body"] = JSON.stringify(params)
    // }

    const api = await fetch(url, options);

    console.log('api : ', api);

    const response = await api.json();

    if (api.status == 422 && response.validations) {
        const validations = response.validations
        Object.keys(validations).map(field => {
            $(`#input-${field}`).addClass("is-invalid");
            $(`#input-${field}`).after(`<div class="invalid-feedback">${validations[field][0]}</div>`);
        });
        throw new Error(JSON.stringify(response.validations));
    }

    if (api.status !== 200) {
        showFailedAlert(response.message);
        throw new Error(response.message);
    }

    console.log('response : ', response);

    return response;
}



function selectGlobalRefresh() {
    $('.select2').select2({
      tags: true,
      placeholder: "Select an Option",
      allowClear: true,
      width: '100%'
    });
}

const numberOnly = (evt) => {
    const charCode = evt.which ? evt.which : evt.keyCode;
    if ( charCode > 31 && (charCode < 48 || charCode > 57) ) {
        evt.preventDefault();
    }
}

"use strict";
