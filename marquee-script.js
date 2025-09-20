document.addEventListener("DOMContentLoaded", function() {
    const marqueeText = marquee_params.marqueeText;

    if (marqueeText && marqueeText.trim() !== "") {
        let marquee = document.createElement("div");
        marquee.id = "announcement-banner";
        marquee.className = "announcement-banner";
        marquee.innerHTML = `<div id="announcement-text" class="announcement-text">${marqueeText}</div>
            <button id="close-banner" class="close-banner">&times;</button>`;
        document.body.insertAdjacentElement("afterbegin", marquee);

        // Add class to body to adjust content position
        document.body.classList.add('has-announcement');

        // Close button functionality with session storage
        const closeButton = marquee.querySelector('.close-banner');
        const bannerId = 'announcement-banner'; // Unique ID for session storage

        if (closeButton) {
            closeButton.addEventListener('click', () => {
                marquee.style.display = 'none';
                document.body.classList.remove('has-announcement');

                // Store in session storage
                sessionStorage.setItem(bannerId, 'closed');
            });
        }

        // Check if banner was closed in the current session
        if (sessionStorage.getItem(bannerId) === 'closed') {
            marquee.style.display = 'none';
            document.body.classList.remove('has-announcement');
        }
    }
});