document.addEventListener('DOMContentLoaded', function() {
    let currentRoom = '';
    let lastSentMessage = null; 

    const userDiv = document.getElementById("userData");
    const client = userDiv.getAttribute("data-client");

    const chatContainer = document.getElementById('supportChatContainer');
    const statusEl = document.getElementById('supportStatus');
    const messagesEl = document.getElementById('supportMessages');
    const messageForm = document.getElementById('supportMessageForm');
    const messageInput = document.getElementById('supportMessage');
    const roomInput = document.getElementById('supportRoomInput');
    const roomChoice = document.getElementById('supportRoomChoice');
    
    const socket = new WebSocket('ws://localhost:8080');
    
    socket.addEventListener('open', function(event) {
        statusEl.textContent = 'Connected';
        addMessage('System', 'Connected to server');
        const message = JSON.stringify({
            action: 'join',
            room: client
        });
        currentRoom = client;
        getRoomMessages(currentRoom);
        socket.send(message);
        addMessage('System', 'Connected to room ' + client);
        document.querySelector('#chatHeader h3').textContent = 'Chat - ' + client;
    });
    
    socket.addEventListener('message', function(event) {
        try {
            const data = JSON.parse(event.data);
            
            if (lastSentMessage && 
                data.msg === lastSentMessage.msg && 
                data.room === lastSentMessage.room) {
                console.log("Message déjà affiché, ignoré");
                return;
            }
            
            if (data.room && data.msg) {
                addMessage('Received', data.msg);
            } else {
                addMessage('Received', event.data);
            }
        } catch (e) {
            addMessage('Received', event.data);
        }
    });
    
    socket.addEventListener('close', function(event) {
        statusEl.textContent = 'Disconnected';
        addMessage('System', 'Disconnected from server');
    });
    
    socket.addEventListener('error', function(event) {
        statusEl.textContent = 'Error';
        addMessage('System', 'Connection error');
    });
    
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (messageInput.value && socket.readyState === WebSocket.OPEN) {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');

            const time = `${hours}:${minutes}:${seconds}`;

            const messageObj = {
                action: 'msg',
                room: currentRoom,
                msg: messageInput.value,
                date: time,
                author: client
            };
            
            lastSentMessage = messageObj;
            
            // Envoyer le message
            socket.send(JSON.stringify(messageObj));
            
            addMessage('Sent', messageInput.value);
            messageInput.value = '';
        }
    });
    
    roomChoice.addEventListener('submit', function(e) {
        e.preventDefault();
        if (roomInput.value && socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({
                action: 'join',
                room: roomInput.value
            });
            currentRoom = roomInput.value;
            getRoomMessages(currentRoom);
            socket.send(message);
            addMessage('System', 'Connected to room ' + roomInput.value);
            document.querySelector('#chatHeader h3').textContent = 'Chat - ' + roomInput.value;
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
        
        messageEl.textContent = sender === 'System' ? message : `${message}`;
        messagesEl.appendChild(messageEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        
        if (sender === 'Received' && !chatContainer.classList.contains('active')) {
            chatButton.classList.add('new-message');
        }
    }

    async function getRoomMessages(room) {
        try {
            const response = await fetch(`http://localhost:5000/api/getRoomMsg?room=${room}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            });
    
            if (!response.ok) {
                throw new Error(`Erreur HTTP! Statut: ${response.status}`);
            }
    
            const data = await response.json();
            console.log("Messages reçus :", data);
            messagesEl.innerHTML = "";
            data.forEach(message => {
                if(message.author == client){
                    addMessage('Sent', message.content);
                } else {
                    addMessage('Received', message.content);
                }
            });
    
        } catch (error) {
            console.error("Erreur lors de la récupération des messages :", error);
        }
    }

});