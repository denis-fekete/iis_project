 function createHttpRequestObj() {
    try {
        var request = new XMLHttpRequest();
    } catch(e) {
        // err handling
    }

    if (!request)
    {
        alert("Error creating the XMLHttpRequest object.");
    }
    else
    {
        return request;
    }
}

function send(formName) {
    var form = new FormData(document.getElementById(formName));

    const formData = JSON.stringify({
        "_token": "{{ csrf_token() }}",
        name: form.get('name'),
        email: form.get('email'),
        password: form.get('password'),
        password_confirmation: form.get('password_confirmation'),
    });

    var request = createHttpRequestObj();

    request.open("POST", "http://127.0.0.1:80/api/register", true);

    request.setRequestHeader("Content-Type", "application/json");
    request.setRequestHeader('Access-Control-Allow-Origin', '*');
    request.setRequestHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    request.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");

    request.onreadystatechange = function() // anonymous function (a function without a name).
    {
        if(request.readyState == 4) {
            if (request.status == 200) { // process is completed and http status is OK
                console.log(request.responseText);
            } else {
                console.log("POST Request failed");
            }
        }
    }

    request.send(formData);
}
