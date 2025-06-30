document.querySelector('.chat-form')?.addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);
    const chat_history = document.querySelector('.chat-history');
    const message_input = form.querySelector('input[name="message"]');

    document.addEventListener('DOMContentLoaded', function() {
        const chat_history = document.querySelector('.chat-history');
        if (chat_history) {
            chat_history.scrollTop = chat_history.scrollHeight;
        }
    });

    const res = await fetch(form.action, {
        method: 'POST',
        body: data
    });

    const result = await res.json();

    if (result.status === 'success') {
        const message_div = document.createElement('div');

        message_div.className = 'chat-message user';
        message_div.innerHTML = `
            <div class="chat-meta">You - just now</div>
            ${message_input.value.replace(/</g, "&lt;").replace(/>/g, "&gt;")}
        `;

        chat_history.appendChild(message_div);
        chat_history.scrollTop = chat_history.scrollHeight;

        message_input.value = '';
    } else {
        alert(result.message || "Failed to send message.");
    }
});

const chat_history = document.querySelector('.chat-history');
const new_chat_button = document.getElementById('new-chat-btn');
const new_chat = document.getElementById('new-chat-modal');
const user_search = document.getElementById('user-search');
const user_list = document.getElementById('user-list');

document.addEventListener('DOMContentLoaded', function () {
    const chat_history = document.querySelector('.chat-history');

    if (chat_history) {
        chat_history.scrollTop = chat_history.scrollHeight;
    }
});

function close_new_chat() {
    new_chat.style.display = 'none';
    user_search.value = '';

    Array.from(user_list.children).forEach(li => li.style.display = '');
}

new_chat_button.onclick = () => {
    new_chat.style.display = 'flex';
    user_search.focus();
};

user_search.addEventListener('input', function() {
    const val = this.value.toLowerCase();

    Array.from(user_list.children).forEach(li => {
        if (li.textContent.toLowerCase().includes(val)) {
            li.style.display = '';
        } else {
            li.style.display = 'none';
        }
    });
});

new_chat.onclick = function(e) {
    if (e.target === new_chat) close_new_chat();
};