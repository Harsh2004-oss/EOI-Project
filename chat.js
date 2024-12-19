const messagesDiv = document.getElementById('chat-messages');
const userInput = document.getElementById('user-input');

// Predefined Q&A data
const qaPairs = {
    "hello": "Hi there! How can I help you today?",
    "how are you": "I'm just a bot, but I'm doing great! Thanks for asking.",
    "what is your name": "I'm a CartBot to assist you.",
    "bye": "Goodbye! Have a great day!",
};

// Function to send a message
function sendMessage() {
    const message = userInput.value.trim().toLowerCase(); // Convert to lowercase for consistent matching
    if (!message) return;

    // Display user message
    addMessageToChat(message, 'user');
    userInput.value = '';

    // Generate bot response
    setTimeout(() => {
        const response = getBotResponse(message);
        addMessageToChat(response, 'bot');
    }, 500);
}

// Function to get bot response
function getBotResponse(input) {
    if (qaPairs[input]) {
        return qaPairs[input]; // Return predefined answer if found
    }
    return "I'm sorry, I don't understand that. Can you ask something else?";
}

// Function to add message to chat
function addMessageToChat(message, sender) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}`;
    messageDiv.innerText = message;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}
