function chargerNotifications() {
    fetch('/notifications')
        .then(function (reponse) { return reponse.json(); })
        .then(afficherNotifications)
        .catch(function () { console.error('Notifications indisponibles'); });
}

function afficherNotifications(donnees) {
    var liste = document.getElementById('liste-notifications');
    var badge = document.getElementById('badge-notifications');
    var compteur = document.getElementById('nb-notifications-lues');

    if (liste) {
        if (donnees.notifications.length === 0) {
            liste.innerHTML = '<p class="text-sm text-gray-400 text-center py-10">Aucune notification.</p>';
        } else {
            liste.innerHTML = donnees.notifications.map(function (n) {
                var contenu = '<div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition ' + (n.lue ? 'opacity-60' : '') + '">'
                    + '<p class="text-sm text-gray-800">' + (n.message || '') + '</p>'
                    + '<p class="text-[11px] text-gray-400 mt-1">' + (n.date || '') + '</p>'
                    + '</div>';
                if (n.lien) {
                    return '<a href="' + n.lien + '" data-notification-id="' + n.id + '" class="block' + (n.lue ? ' opacity-60' : '') + '">' + contenu + '</a>';
                }
                return '<div>'+ contenu + '</div>';
            }).join('');
        }
    }

    if (badge) {
        if (donnees.non_lues > 0) {
            badge.textContent = donnees.non_lues;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    if (compteur) {
        compteur.textContent = donnees.non_lues > 0 ? donnees.non_lues + ' non lue(s)' : 'Tout est à jour';
    }
}

window.basculerNotifications = function () {
    var panneau = document.getElementById('panneau-notifications');
    if (panneau) {
        panneau.classList.toggle('hidden');
    }
};

document.addEventListener('click', function (event) {
    var bouton = document.getElementById('btn-notifications');
    var panneau = document.getElementById('panneau-notifications');
    if (bouton && panneau && !bouton.contains(event.target) && !panneau.contains(event.target)) {
        panneau.classList.add('hidden');
    }
});

document.addEventListener('click', function (event) {
    var lien = event.target.closest('[data-notification-id]');
    if (!lien) return;
    var id = lien.getAttribute('data-notification-id');
    var badge = document.getElementById('badge-notifications');
    var panneau = document.getElementById('panneau-notifications');
    fetch('/notifications/' + id + '/lue', { method: 'POST' })
        .then(function () {
            if (badge && !badge.classList.contains('hidden')) {
                var restant = parseInt(badge.textContent, 10) - 1;
                if (restant <= 0) {
                    badge.classList.add('hidden');
                } else {
                    badge.textContent = restant;
                }
            }
            chargerNotifications();
        })
        .catch(function () {});
}, true);

chargerNotifications();
setInterval(chargerNotifications, 30000);