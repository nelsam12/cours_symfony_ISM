document.addEventListener('DOMContentLoaded', function () {
    const checkAddUser = document.querySelector('#client_addUser');
    
    const userForm = document.querySelector('#client_user');
    userForm.classList.add('d-none');
    showFormUser(checkAddUser);

    checkAddUser.addEventListener('change', (e) => {
        
        showFormUser(checkAddUser);

    })

    
    function showFormUser(checkbox){
        if (checkbox.checked) {
            userForm.classList.remove('d-none');
        }else{
            userForm.classList.add('d-none');
        }
    }
    
})