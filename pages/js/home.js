let current = 0;
const img = document.getElementById('gallery-image');
const dots = document.querySelectorAll('.gallery-dot');

function show_image(idx) {
    img.src = images[idx];
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === idx);
    });
    current = idx;
}

dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
        show_image(i);
    });
});

setInterval(() => {
    show_image((current + 1) % images.length);
}, 5000);