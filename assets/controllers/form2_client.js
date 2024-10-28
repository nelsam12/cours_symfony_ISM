document.addEventListener('DOMContentLoaded', function () {
    const AddUser = document.querySelector('#client_addUser');
    const formClient = document.getElementsByName('client')[0];
    // On attend juste que la personne clique le champ de chekbox, et s'il check on soumet le formulaire
    AddUser.addEventListener('change', (e) => {
        // sI on envoie ici
        formClient.submit();
    })


})