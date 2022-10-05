var usersToDelete = {};
function registerForm() {
    var form = document.querySelector('form');
    form.hidden = !form.hidden;
}
function register() {
    var api = ApiRequest('/admin/api/register');
    api.postForm(({ok}) => {
        if(ok) {
            document.querySelector('form').hidden = true;
            usersTable();
        }
    });
}
function checkToDelete(checkbox) {
    usersToDelete[checkbox.value] = checkbox.checked;
}
function unregister() {
    var users = [];
    for(var i in usersToDelete) {
        if(usersToDelete[i]) {
            users.push(i);
        }
    }
    var api = ApiRequest('/admin/api/unregister');
    api.postJSON(users,usersTable);
}
function usersTable() {
    var api = ApiRequest('/admin/api/users');
    api.getJSON(({data}) => {
        var table = document.querySelector('table');
        var html = `
        <table>
        <tr>
            <td>Email</td>
            <td>КИМ</td>
            <td>Отметка на удаление</td>
        </tr>
        `;
        for(var i in data.emails) {
            html +=
            '<tr>'
            +'<td>'+data.emails[i]+'</td>'
            +'<td>'+data.kims[i]+'</td>'
            +'<td><input onchange="checkToDelete(this)" type="checkbox" value="'+data.emails[i]+'"></input></td>'
            +'</tr>'
        }
        html += `
        <tr>
        <td></td>
        <td><button onclick='registerForm()'>Добавить</button></td>
        <td><button onclick='unregister()'>Удалить</button></td>
        </tr>
        `;
        table.innerHTML = html;
    });
}
usersTable();