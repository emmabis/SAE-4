document.addEventListener('DOMContentLoaded', function() {
    const chatButton = document.getElementById('chatButton');
    const chatContainer = document.getElementById('chatContainer');
    const messagesEl = document.getElementById('messages');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('message');
    
    chatButton.addEventListener('click', function() {
        chatContainer.classList.toggle('active');
        if (chatContainer.classList.contains('active')) {
            messageInput.focus();
            chatButton.classList.remove('new-message');
        }
    });
    
    function showTypingIndicator() {
        const typingEl = document.createElement('div');
        typingEl.className = 'typing-indicator';
        typingEl.id = 'typingIndicator';
        
        for (let i = 0; 3 > i; i++) {
            const dot = document.createElement('span');
            typingEl.appendChild(dot);
        }
        
        messagesEl.appendChild(typingEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        return typingEl;
    }
    
    function hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) {
            indicator.remove();
        }
    }
    
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (messageInput.value) {
            const userMessage = messageInput.value;
            
            addMessage('Sent', userMessage);
            
            messageInput.value = '';
            messageInput.disabled = true;
            messageForm.querySelector('button').disabled = true;
            
            const typingIndicator = showTypingIndicator();
            
            try {
                const response = await fetch("http://localhost:11434/api/generate", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        model: "meteo-assistant",
                        prompt: userMessage,
                        stream: false 
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                
                const data = await response.json();
                
                hideTypingIndicator();

                addMessage('Received', data.response.replace(/\. /g, '.\n'));
            } catch (error) {
                console.error("Erreur lors de l'appel API:", error);
                
                hideTypingIndicator();
                
                addMessage('System', `Une erreur s'est produite: ${error.message}`);
            } finally {
                messageInput.disabled = false;
                messageForm.querySelector('button').disabled = false;
                messageInput.focus();
            }
        }
    });

    function addMessage(sender, message) {
        const messageEl = document.createElement('div');
        
        if (sender === 'System') {
            messageEl.className = 'message system';
        } else if (sender === 'Sent') {
            messageEl.className = 'message sent';
        } else {
            messageEl.className = 'message received';
        }
        
        // Sécurise le HTML et convertit les \n en <br>
        const sanitizedMessage = message
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")
            .replace(/\n/g, "<br>");
        
        messageEl.innerHTML = sanitizedMessage;
        messagesEl.appendChild(messageEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        
        if (sender === 'Received' && !chatContainer.classList.contains('active')) {
            chatButton.classList.add('new-message');
        }
    }

    addMessage('Received', "Salut je suis la meilleur IA de France sur la météo ! Que puis-je faire pour toi ?");
});