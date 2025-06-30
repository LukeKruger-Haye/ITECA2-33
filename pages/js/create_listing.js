const upload_area = document.getElementById('upload-area');
const file_input = document.getElementById('images');
const preview_container = document.getElementById('file-preview');
const form = document.getElementById('listing-form');
const submit_btn = document.getElementById('submit-button');
const message_container = document.getElementById('message-container');

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
        if (selected_files.length >= max_files) {
            show_message('Maximum 10 images allowed!', 'error');
            return;
        }
        
        if (!file.type.startsWith('image/')) {
            show_message(`${file.name} is not an image!`, 'error');
            return;
        }
        
        if (file.size > max_size) {
            show_message(`${file.name} is too large! Max 5MB per image.`, 'error');
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
            <button type="button" onclick="remove_image(this, '${file.name}')" class="remove-image">×</button>
        `;

        preview_container.appendChild(preview);
    };

    reader.readAsDataURL(file);
}

function remove_image(button, file_name) {
    button.parentElement.remove();
    selected_files = selected_files.filter(file => file.name !== file_name);
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (selected_files.length === 0) {
        alert('Please upload at least one image!');
        return;
    }
    
    const form_data = new FormData();

    form_data.append('name', document.getElementById('name').value);
    form_data.append('price', document.getElementById('price').value);
    form_data.append('description', document.getElementById('description').value);
    
    selected_files.forEach(file => {
        form_data.append('images[]', file);
    });
    
    submit_btn.disabled = true;
    
    try {
        const response = await fetch('../api/listings/create_listing.php', {
            method: 'POST',
            body: form_data
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert(result.message);

            form.reset();
            selected_files = [];
            preview_container.innerHTML = '';

            window.location.href = '../index.php';
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Something went wrong! Please try again.');
    } finally {
        submit_btn.disabled = false;
    }
});