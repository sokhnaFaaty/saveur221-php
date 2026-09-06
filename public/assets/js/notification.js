async function chargerNotifications() {
    try {
        const reponse = await fetch('/notifications');
        const donnees = await reponse.json();

        const badge = document.getElementById('badge-notifications');
        if (donnees.non_lues > 0) {
            badge.textContent = donnees.non_lues;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    } catch (e) {
        console.error('Notifications indisponibles', e);
    }
}

chargerNotifications();
setInterval(chargerNotifications, 30000); // rafraichi toutes les 30 secondes