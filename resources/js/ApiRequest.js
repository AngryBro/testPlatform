class ApiRequest {
    constructor(url = '/api'+new URL(window.location).pathname) {
        this.url = url;
    }
    async getJSON(func) {
        var promise = await fetch(this.url);
        if(arguments.length<1) {
            return;
        }
        var status = promise.status;
        var ok = promise.ok;
        var response = await promise.json();
        var data = {ok:ok,status:status,data:response};
        func(data);
    }
    async postJSON(object,func) {
        var form = new FormData();
        form.set('json',JSON.stringify(object));
        var promise = await fetch(this.url,{
            method: 'post',
            body: form,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        if(arguments.length<2) {
            return;
        }
        var status = promise.status;
        var ok = promise.ok;
        var response = await promise.json();
        var data = {ok:ok,status:status,data:response};
        func(data);
    }
    async postForm(func) {
        var form = document.querySelector('form');
        var promise = await fetch(this.url,{
            method: 'post',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        var status = promise.status;
        var ok = promise.ok;
        if(arguments.length<1) {
            return;
        }
        var response = await promise.json();
        if(ok) {
            var data = {ok:ok,status:status,data:response};
            func(data);
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
}