ApiRequest = (url = '/api'+new URL(window.location).pathname) => {
    var getJSON = async (callback) => {
        var promise = await fetch(url);
        if(callback===undefined) {
            return;
        }
        var status = promise.status;
        var ok = promise.ok;
        var response = await promise.json();
        var data = {ok:ok,status:status,data:response};
        callback(data);
    }
    var postJSON = async (object,callback) => {
        var form = new FormData();
        form.set('json',JSON.stringify(object));
        var promise = await fetch(url,{
            method: 'post',
            body: form,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        if(callback===undefined) {
            return;
        }
        var status = promise.status;
        var ok = promise.ok;
        var response = await promise.json();
        var data = {ok:ok,status:status,data:response};
        callback(data);
    }
    var postForm = async (callback) => {
        var form = document.querySelector('form');
        var promise = await fetch(url,{
            method: 'post',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        var status = promise.status;
        var ok = promise.ok;
        if(callback===undefined) {
            return;
        }
        var response = await promise.json();
        if(ok) {
            var data = {ok:ok,status:status,data:response};
            callback(data);
        }
        else {
            if(status==422) {
                var errors = [];
                for(var i in response) {
                    for(var j in response[i]) {
                        errors.push(response[i][j]);
                    }
                }
                errors = errors.join('\n');
                alert(errors);
            }
        }
    }
    return Object.freeze({
        getJSON,
        postJSON,
        postForm
    });
}