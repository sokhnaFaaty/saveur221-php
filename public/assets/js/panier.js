const CLE_PANIER = 'saveur221_panier';

function lirePanier() {
    try { return JSON.parse(localStorage.getItem(CLE_PANIER)) || []; }
    catch { return []; }
}

function ecrirePanier(panier) {
    localStorage.setItem(CLE_PANIER, JSON.stringify(panier));
    mettreAJourAffichage();
}

function ajouterAuPanier(produit, quantite = 1) {
    const panier = lirePanier();
    const existant = panier.find((item) => item.id == produit.id);

    if (existant) {
        existant.quantite += quantite;
    } else {
        panier.push({ id: produit.id, nom: produit.nom, prix: parseFloat(produit.prix), image: produit.image, quantite });
    }

    ecrirePanier(panier);
    ouvrirPanier();
}

function changerQuantite(id, delta) {
    const panier = lirePanier();
    const item = panier.find((i) => i.id == id);
    if (!item) return;
    item.quantite += delta;
    ecrirePanier(item.quantite <= 0 ? panier.filter((i) => i.id != id) : panier);
}

function retirerDuPanier(id) {
    ecrirePanier(lirePanier().filter((i) => i.id != id));
}

function viderPanier() {
    ecrirePanier([]);
}

function ouvrirPanier() {
    document.getElementById('panneau-panier')?.classList.remove('translate-x-full');
    document.getElementById('overlay-panier')?.classList.remove('hidden');
}

function fermerPanier() {
    document.getElementById('panneau-panier')?.classList.add('translate-x-full');
    document.getElementById('overlay-panier')?.classList.add('hidden');
}

function mettreAJourAffichage() {
    const panier = lirePanier();
    const badge = document.getElementById('badge-panier');
    const totalArticles = panier.reduce((s, i) => s + i.quantite, 0);

    if (badge) {
        badge.textContent = totalArticles;
        badge.classList.toggle('hidden', totalArticles === 0);
    }

    const liste = document.getElementById('liste-panier');
    if (!liste) return;

    liste.innerHTML = panier.length === 0
        ? '<p class="text-center text-gray-400 py-10 text-sm">Votre panier est vide.</p>'
        : panier.map((item) => `
            <div class="flex items-center gap-3 py-3 border-b border-gray-50">
                <img src="${item.image || '/assets/img/produits/thieboudienneRouge.jpg'}" class="w-14 h-14 rounded-lg object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">${item.nom}</p>
                    <p class="text-primary font-bold text-sm">${item.prix.toLocaleString()} FCFA</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <button onclick="changerQuantite(${item.id}, -1)" class="w-6 h-6 rounded border border-gray-200 text-xs">-</button>
                    <span class="text-sm w-4 text-center">${item.quantite}</span>
                    <button onclick="changerQuantite(${item.id}, 1)" class="w-6 h-6 rounded border border-gray-200 text-xs">+</button>
                </div>
                <button onclick="retirerDuPanier(${item.id})" class="text-red-400 hover:text-red-600 text-sm px-1">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </div>
        `).join('');

    const total = panier.reduce((s, i) => s + i.prix * i.quantite, 0);
    const totalEl = document.getElementById('total-panier');
    if (totalEl) totalEl.textContent = total.toLocaleString() + ' FCFA';

    const boutonValider = document.getElementById('btn-valider-panier');
    if (boutonValider) boutonValider.disabled = panier.length === 0;
}

async function validerPanier() {
    const panier = lirePanier();
    if (panier.length === 0) return;

    const bouton = document.getElementById('btn-valider-panier');
    bouton.disabled = true;
    bouton.textContent = 'Validation en cours...';

    try {
        const reponse = await fetch('/commandes', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ lignes: panier.map((i) => ({ produit_id: i.id, quantite: i.quantite })) }),
        });

        if (reponse.redirected) {
            viderPanier();
            window.location.href = reponse.url;
        } else {
            window.location.reload();
        }
    } catch {
        alert('Erreur lors de la validation de la commande.');
        bouton.disabled = false;
        bouton.textContent = 'Valider mon panier';
    }
}

document.addEventListener('DOMContentLoaded', mettreAJourAffichage);