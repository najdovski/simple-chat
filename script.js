loadMessages();
setInterval(() => { loadMessages() }, 300);

const name = prompt('Your name?');
const form = document.querySelector('form');
const message = document.querySelector('#message');

form.addEventListener('submit', (event) => {
  event.preventDefault();
  http = new XMLHttpRequest();
  http.open('POST', 'chat.php', true);
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  http.onreadystatechange = function() {
    if(http.readyState === 4 && http.status === 200) {
      loadMessages();
      message.value = '';
    }
  }
  http.send(`name=${name}&message=${message.value}`);
});

function loadMessages() {
  const http = new XMLHttpRequest();
  http.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
      if (http.response) {
        const fetchedMessages = JSON.parse(http.response).messages;
        renderMessage(fetchedMessages);
      }
    }
  };
  http.open('GET', 'chat.php', true);
  http.send();
}

function renderMessage(messages) {
  if (!messages) return;
  const fetchedMessages = document.querySelector('#fetchedMessages');
  fetchedMessages.innerHTML = '';
  messages.forEach((message) => {
    fetchedMessages.innerHTML += `<div class="singleFetchedMessage"><strong>${message.name}: </strong> ${message.message} <i> (${message.timestamp}) </i></div>`;
  });
}