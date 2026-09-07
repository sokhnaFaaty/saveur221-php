function demanderConfirmation({ titre, message, cible, actionUrl }) {
    const modal = document.getElementById('modal-confirmation');
    modal.querySelector('[data-titre]').textContent = titre;
    modal.querySelector('[data-message]').textContent = message;
    modal.querySelector('[data-cible]').textContent = cible;

    const formulaire = modal.querySelector('form');
    formulaire.action = actionUrl;

    modal.classList.remove('hidden');
}

function fermerConfirmation() {
    document.getElementById('modal-confirmation').classList.add('hidden');
}