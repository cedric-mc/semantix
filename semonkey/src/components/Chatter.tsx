import React, { useState, useEffect } from 'react';
import ChatLauncher from './ChatLauncher';
import ChatMessageDisplayer from './ChatMessageDisplayer';
import ChatSender from './ChatSender';
import ChatManager from './ChatManager';

const Chatter = ({ chatManager }) => {
    const [messages, setMessages] = useState([]);
    const [chatStarted, setChatStarted] = useState(false);

    // Fonction pour ouvrir le chat
    const handleChatStarted = (name, email) => {
        chatManager.open();
        setChatStarted(true);
    };

    // Définit le récepteur de message
    useEffect(() => {
        const messageReceiver = (content) => {
            setMessages(prevMessages => [...prevMessages, { kind: 'received', content, date: new Date() }]);
        };
        chatManager.setMessageReceiver(messageReceiver);

        return () => {
            chatManager.setMessageReceiver(null); // Nettoyage du récepteur lors du démontage du composant
        };
    }, [chatManager]);

    // Fonction pour envoyer un message
    const handleMessageEntered = (content) => {
        chatManager.sendMessage(content);
        setMessages(prevMessages => [...prevMessages, { kind: 'sent', content, date: new Date() }]);
    };

    return (
        <div>
            {!chatStarted ? (
                <ChatLauncher initialName="" initialEmail="" onChatStarted={handleChatStarted} />
            ) : (
                <div>
                    <ChatMessageDisplayer messages={messages} />
                    <ChatSender onMessageEntered={handleMessageEntered} />
                </div>
            )}
        </div>
    );
};

export default Chatter;
