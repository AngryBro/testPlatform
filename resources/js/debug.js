form = new FormData();
form.set('email','admin@admin.ru');
form.set('password','admin');
fetch('/api/login',{
    method: 'post',
    body: form,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});