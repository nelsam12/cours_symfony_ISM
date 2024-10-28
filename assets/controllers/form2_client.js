document.addEventListener('DOMContentLoaded', function () {
    const AddUser = document.querySelector('#client_addUser');
    const formClient = document.getElementsByName('client')[0];
    AddUser.addEventListener('change', (e) => {
        formClient.submit();
    })


})