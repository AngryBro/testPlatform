var kimsToDelete = {};
function addForm() {
    var form = document.querySelector('form');
    form.hidden = !form.hidden;
}
function add() {
    var api = ApiRequest('/admin/api/addkim');
    api.postForm(({ok}) => {
        if(ok) {
            document.querySelector('form').hidden = true;
            kimsTable();
        }
    });
}
function checkToDelete(checkbox) {
    kimsToDelete[checkbox.value] = checkbox.checked;
}
function del() {
    var kims = [];
    for(var i in kimsToDelete) {
        if(kimsToDelete[i]) {
            kims.push(i);
        }
    }
    var api = ApiRequest('/admin/api/delkims');
    api.postJSON(kims,kimsTable);
}
function kimsTable() {
    var api = ApiRequest('/admin/api/kims');
    api.getJSON(({data}) => {
        var table = document.querySelector('table');
        var html = `
        <table>
        <tr>
            <td>КИМ</td>
            <td>Отметка на удаление</td>
        </tr>
        `;
        for(var i in data) {
            html +=
            '<tr>'
            +'<td>'+data[i]+'</td>'
            +'<td><input onchange="checkToDelete(this)" type="checkbox" value="'+data[i]+'"></input></td>'
            +'</tr>'
        }
        html += `
        <tr>
        <td><button onclick='addForm()'>Добавить</button></td>
        <td><button onclick='del()'>Удалить</button></td>
        </tr>
        `;
        table.innerHTML = html;
    });
}
kimsTable();
