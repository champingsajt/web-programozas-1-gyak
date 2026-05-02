document.addEventListener('DOMContentLoaded', () => {
    loadCounters();
    initTheme();

    // Theme selector listener
    document.getElementById('themeSelector').addEventListener('change', function() {
        setTheme(this.value);
    });
});

let countdownInterval;

function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    document.getElementById('themeSelector').value = savedTheme;
}

function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

// Fetch Counters
async function loadCounters() {
    try {
        const response = await fetch('api/counters.php');
        if (!response.ok) throw new Error('Network response was not ok');
        const counters = await response.json();
        renderCounters(counters);
    } catch (error) {
        console.error('Error fetching counters:', error);
    }
}

function renderCounters(counters) {
    const container = document.getElementById('countersContainer');
    container.innerHTML = '';

    counters.forEach(counter => {
        const card = document.createElement('div');
        card.className = 'card';
        card.setAttribute('data-deadline', counter.deadline);
        
        card.innerHTML = `
            <div class="card-actions">
                <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${counter.id}, '${counter.title.replace(/'/g, "\\'")}', '${counter.deadline}', '${(counter.status_message || '').replace(/'/g, "\\'")}')">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="openDeleteModal(${counter.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <h2>${escapeHtml(counter.title)}</h2>
            <div class="countdown">
                <span class="days">00</span> nap 
                <span class="hours">00</span>:
                <span class="minutes">00</span>:
                <span class="seconds">00</span>
            </div>
            <p class="status">${escapeHtml(counter.status_message || '')}</p>
        `;
        container.appendChild(card);
    });

    if (countdownInterval) clearInterval(countdownInterval);
    countdownInterval = setInterval(updateCountdowns, 1000);
    updateCountdowns(); // Initial call
}

function updateCountdowns() {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        const deadlineAttr = card.getAttribute('data-deadline');
        // Convert YYYY-MM-DD HH:MM:SS to a format Date() understands cross-browser safely
        const deadline = new Date(deadlineAttr.replace(' ', 'T')).getTime();
        const now = new Date().getTime();
        const diff = deadline - now;

        if (diff > 0) {
            const totalSeconds = Math.round(diff / 1000);
            const d = Math.floor(totalSeconds / (60 * 60 * 24));
            const h = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
            const m = Math.floor((totalSeconds % (60 * 60)) / 60);
            const s = totalSeconds % 60;

            card.querySelector('.days').innerText = d.toString().padStart(2, '0');
            card.querySelector('.hours').innerText = h.toString().padStart(2, '0');
            card.querySelector('.minutes').innerText = m.toString().padStart(2, '0');
            card.querySelector('.seconds').innerText = s.toString().padStart(2, '0');
        } else {
            card.querySelector('.countdown').innerHTML = "LEJÁRT! 💀";
            card.style.borderColor = "var(--danger-color)";
        }
    });
}

// Modal functions
const counterModal = new bootstrap.Modal(document.getElementById('counterModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

function openAddModal() {
    document.getElementById('counterForm').reset();
    document.getElementById('counterId').value = '';
    document.getElementById('modalTitle').innerText = 'Új Számláló';
}

function openEditModal(id, title, deadline, statusMessage) {
    document.getElementById('counterId').value = id;
    document.getElementById('title').value = title;
    
    // Convert YYYY-MM-DD HH:MM:SS to YYYY-MM-DDTHH:MM for datetime-local input
    document.getElementById('deadline').value = deadline.replace(' ', 'T');
    
    document.getElementById('status_message').value = statusMessage;
    document.getElementById('modalTitle').innerText = 'Számláló Módosítása';
    counterModal.show();
}

function openDeleteModal(id) {
    document.getElementById('deleteCounterId').value = id;
    deleteModal.show();
}

async function saveCounter() {
    const id = document.getElementById('counterId').value;
    const title = document.getElementById('title').value;
    const deadline = document.getElementById('deadline').value;
    const statusMessage = document.getElementById('status_message').value;

    if (!title || !deadline) {
        alert("A cím és a határidő megadása kötelező!");
        return;
    }

    const payload = {
        title: title,
        deadline: deadline.replace('T', ' ') + ':00', // Convert back to MySQL DATETIME format
        status_message: statusMessage
    };

    let url = 'api/counters.php';
    let method = 'POST';

    if (id) {
        payload.id = id;
        method = 'PUT';
    }

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            counterModal.hide();
            loadCounters();
        } else {
            alert("Hiba történt a mentés során.");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

async function confirmDelete() {
    const id = document.getElementById('deleteCounterId').value;
    
    try {
        const response = await fetch('api/counters.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });

        if (response.ok) {
            deleteModal.hide();
            loadCounters();
        } else {
            alert("Hiba történt a törlés során.");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

function escapeHtml(unsafe) {
    return (unsafe || '').toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}
