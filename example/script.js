function updateCountdowns() {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        const deadline = new Date(card.getAttribute('data-deadline')).getTime();
        const now = new Date().getTime();
        const diff = deadline - now;

        if (diff > 0) {
            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            card.querySelector('.days').innerText = d.toString().padStart(2, '0');
            card.querySelector('.hours').innerText = h.toString().padStart(2, '0');
            card.querySelector('.minutes').innerText = m.toString().padStart(2, '0');
            card.querySelector('.seconds').innerText = s.toString().padStart(2, '0');
        } else {
            card.querySelector('.countdown').innerHTML = "LEJÁRT! 💀";
            card.style.borderColor = "#ff0000";
        }
    });
}

// Frissítés másodpercenként
setInterval(updateCountdowns, 1000);
updateCountdowns(); // Azonnali indítás