import React, {useState, useEffect, useCallback} from 'react';
import ChatLauncher from './ChatLauncher';
import ChatMessageDisplayer from './ChatMessageDisplayer';
import ChatSender from './ChatSender';
import ChatManager from './ChatManager';
import ChatDisplayer from "./ChatDisplayer";
import useWebSocket from "react-use-websocket";

const Chatter = ({ chatManager }) => {
    const [messages, setMessages] = useState<{ kind: string, content: string, date: Date, username: string }[]>([]);
    const [chatStarted, setChatStarted] = useState(false);
    const [username, setUsername] = useState('');

    const {
        sendMessage,
        readyState,
    } = useWebSocket('ws://localhost:2024', {
        onMessage: (event) => {
            const message = JSON.parse(event.data);
            setMessages(prevMessages => [...prevMessages, { kind: 'received', content: message.content, date: new Date(), username: message.username }]);
        },
        shouldReconnect: (closeEvent) => true,
    });

    const handleChatStarted = (name) => {
        setUsername(name);
        setChatStarted(true);
    };

    const handleMessageEntered = useCallback((content: string) => {
        const message = JSON.stringify({ content, username });
        sendMessage(message);
        setMessages(prevMessages => [...prevMessages, { kind: 'sent', content, date: new Date(), username }]);
    }, [sendMessage, username]);

    return (
        <div>
            {!chatStarted ? (
                <ChatLauncher onChatStarted={handleChatStarted} />
            ) : (
                <div>
                    <ChatDisplayer messages={messages} />
                    <ChatSender onMessageEntered={handleMessageEntered} />
                </div>
            )}
        </div>
    );
};

export default Chatter;