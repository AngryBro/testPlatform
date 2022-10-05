function kimName() {
    var url = window.location.href.split('/');
    return url[url.length-1];
}
function isAdmin() {
    var url = window.location.href.split('/');
    return url[3]=='admin';
}
function pageButtons() {
    var api = ApiRequest(isAdmin()?'/admin/api/test/'+kimName()+'/tasks':'/api/test/tasks'+kimName());
    api.getJSON(({data}) => {
        var table = document.querySelector('.pageButtons');
        var html = '';
        for(var i in data) {
            html +=
            '<tr><td><button onclick="showTask(this)" class="pageButton" value="'+data[i]+'">'
            +data[i]
            +'</button></td></tr>';
        }
        table.innerHTML = html;
    });
}
function showTask(button) {
    var number = button.value;
    var lastButton = document.querySelector('.pageButtonSelected');
    if((lastButton!==null)&&(button.getAttribute('class')!='pageButtonSelected')) {
       lastButton.setAttribute('class','pageButton');
    }
    button.setAttribute('class','pageButtonSelected');
    var img = document.querySelector('img');
    var url = isAdmin()?'/admin/api/test/'+kimName()+'/task/'+number
    :'/api/test/task/'+number;
    img.setAttribute('src',url);
}
function clearAnswer() {
    document.querySelector('textarea').value = '';
}
function saveAnswer() {
    if(isAdmin()) return;
    var task = document.querySelector('.pageButtonSelected').value;
    var answer = document.querySelector('textarea').value;
    var api = ApiRequest('/api/test/saveAnswer');
    api.postJSON({task,answer});
}

export default isAdmin