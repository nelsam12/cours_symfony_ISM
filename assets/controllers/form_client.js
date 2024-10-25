document.addEventListener('DOMContentLoaded', function () {
    const checkAddUser = document.querySelector('#client_addUser');
    const userForm = document.querySelector('#client_user');
    checkAddUser.addEventListener('change', (e) => {
        if (e.target.checked) {
            userForm.classList.remove('d-none');
        }else{
            userForm.classList.add('d-none');
        }
    })
})