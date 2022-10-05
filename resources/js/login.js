const login = () => {
    var api = ApiRequest('/api/login');
    api.postForm(({data,ok}) => {
        if(ok) {
            if(data.logged) {
                location.href = data.role=='admin'?'/admin':'/testStart';
            }
            else {
                alert('Неверные данные');
            }
        }
    });
}
function register() {
    var api = ApiRequest('/admin/api/register');
    api.postForm(response => {
        if(response.ok) {
            document.querySelector('form').reset();
            alert('Успешно');
        }
    });
}
function unregister() {
    var emails = [];
    for(var i in emailsToDelete) {
        if(emailsToDelete[i]) {
            emails.push(i);
        }
    }
    var api = ApiRequest('/admin/api/unregister');
    api.postJSON(emails,loadTable);
}