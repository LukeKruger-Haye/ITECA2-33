const upload_area = document.getElementById('upload-area');
const file_input = document.getElementById('images');
const preview_container = document.getElementById('file-preview');
let selected_files = [];
const max_files = 10;
const max_size = 5 * 1024 * 1024;

upload_area.addEventListener('click', () => file_input.click());
upload_area.addEventListener('dragover', (e) => {
    e.preventDefault();
    upload_area.classList.add('dragover');
});
upload_area.addEventListener('dragleave', () => {
    upload_area.classList.remove('dragover');
});
upload_area.addEventListener('drop', (e) => {
    e.preventDefault();
    upload_area.classList.remove('dragover');
    handle_files(e.dataTransfer.files);
});
file_input.addEventListener('change', (e) => {
    handle_files(e.target.files);
});

function handle_files(files) {
    Array.from(files).forEach(file => {
        if (selected_files.length + preview_container.querySelectorAll('.image-preview:not([data-media-id=""])').length >= max_files) {
            alert('Maximum 10 images allowed!');
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert(`${file.name} is not an image!`);
            return;
        }

        if (file.size > max_size) {
            alert(`${file.name} is too large! Max 5MB per image.`);
            return;
        }

        selected_files.push(file);
        add_image_preview(file);
    });
}

function add_image_preview(file) {
    const reader = new FileReader();

    reader.onload = (e) => {
        const preview = document.createElement('div');

        preview.className = 'image-preview';
        preview.innerHTML = `
            <img src="${e.target.result}" alt="${file.name}">
            <button type="button" class="remove-image" onclick="remove_image(this, '${file.name}')">×</button>
        `;

        preview_container.appendChild(preview);
    };

    reader.readAsDataURL(file);
}

window.remove_image = function(button, file_name) {
    button.parentElement.remove();
    selected_files = selected_files.filter(file => file.name !== file_name);
}

window.delete_image = function(media_id, btn) {
    if (!confirm('Delete this image?')) return;

    fetch('../api/listings/delete_image.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'media_id=' + encodeURIComponent(media_id)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            btn.parentElement.remove();
        } else {
            alert('Failed to delete image.');
        }
    });
}

const form = document.querySelector('form');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const form_data = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: form_data
    }).then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '../index.php';
            } else {
                alert(data.message);
            }
        }).catch(() => {
            alert('Failed to update listing.');
        });
});