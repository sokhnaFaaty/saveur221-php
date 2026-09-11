(function () {
    const piste = document.getElementById('piste-avis');
    if (!piste) return;

    const slides = Array.from(piste.children);
    const btnPrev = document.getElementById('btn-avis-prev');
    const btnNext = document.getElementById('btn-avis-next');
    const pointsBox = document.getElementById('points-avis');
    if (slides.length === 0) return;

    let index = 0;
    let timer = null;
    let toucheDepart = null;
    let posDepart = 0;

    function visibles() {
        const w = window.innerWidth;
        if (w >= 1280) return 4;
        if (w >= 1024) return 3;
        if (w >= 640) return 2;
        return 1;
    }

    function maxIndex() {
        return Math.max(0, slides.length - visibles());
    }

    function construitPoints() {
        if (!pointsBox) return;
        pointsBox.innerHTML = '';
        for (let i = 0; i <= maxIndex(); i++) {
            const p = document.createElement('button');
            p.type = 'button';
            p.className = 'w-2 h-2 rounded-full bg-gray-300 transition-all';
            p.setAttribute('aria-label', 'Aller à l\'avis ' + (i + 1));
            p.addEventListener('click', () => {
                index = i;
                actualise();
            });
            pointsBox.appendChild(p);
        }
    }

    function actualise() {
        const nb = visibles();
        const max = maxIndex();
        index = Math.max(0, Math.min(index, max));
        piste.style.transform = 'translateX(-' + index * (100 / nb) + '%)';

        const points = pointsBox ? Array.from(pointsBox.children) : [];
        points.forEach((pt, i) => {
            pt.className = 'w-2 h-2 rounded-full transition-all ' +
                (i === index ? 'w-6 bg-primary' : 'bg-gray-300');
        });
        if (btnPrev) btnPrev.disabled = index === 0;
        if (btnNext) btnNext.disabled = index === max;
    }

    function demarreAuto() {
        arreteAuto();
        timer = setInterval(() => {
            index = index >= maxIndex() ? 0 : index + 1;
            actualise();
        }, 4500);
    }

    function arreteAuto() {
        if (timer) { clearInterval(timer); timer = null; }
    }

    if (btnPrev) btnPrev.addEventListener('click', () => { index = Math.max(0, index - 1); actualise(); demarreAuto(); });
    if (btnNext) btnNext.addEventListener('click', () => { index = Math.min(maxIndex(), index + 1); actualise(); demarreAuto(); });

    piste.addEventListener('touchstart', (e) => {
        toucheDepart = e.touches[0].clientX;
        posDepart = index;
        arreteAuto();
    }, { passive: true });

    piste.addEventListener('touchend', (e) => {
        if (toucheDepart === null) return;
        const delta = e.changedTouches[0].clientX - toucheDepart;
        if (Math.abs(delta) > 40) {
            index = delta < 0 ? Math.min(maxIndex(), posDepart + 1) : Math.max(0, posDepart - 1);
            actualise();
        }
        toucheDepart = null;
        demarreAuto();
    }, { passive: true });

    window.addEventListener('resize', () => { actualise(); });
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) { arreteAuto(); } else { demarreAuto(); }
    });

    const section = piste.closest('section');
    if (section) {
        section.addEventListener('mouseenter', arreteAuto);
        section.addEventListener('mouseleave', demarreAuto);
    }

    construitPoints();
    actualise();
    demarreAuto();
})();