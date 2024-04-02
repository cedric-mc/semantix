import React, {useState, useCallback} from 'react';
import ChatLauncher from './ChatLauncher';
import ChatSender from './ChatSender';
import ChatManager from './ChatManager';
import ChatDisplayer from "./ChatDisplayer";
import useWebSocket from "react-use-websocket";
import {Offcanvas} from "react-bootstrap";
import {User} from "../User";

const Chatter = (props: { chatManager: ChatManager | null, user: User, showChat: boolean, handleShowChat: () => void }) => {
    const [messages, setMessages] = useState<{ kind: 'sent' | 'received', content: string, date: Date, username: string }[]>([]);
    const [chatStarted, setChatStarted] = useState(false);
    const [username, setUsername] = useState('');

    const { sendMessage, readyState, lastMessage, getWebSocket } = useWebSocket('ws://localhost:2024', {
        onMessage: (event) => {
            const message = JSON.parse(event.data);
            setMessages(prevMessages => [...prevMessages, { kind: 'received', content: message.content, date: new Date(), username: message.username }]);
        },
        shouldReconnect: (closeEvent) => true,
    });

    const handleChatStarted = (name: string) => {
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
            <Offcanvas show={props.showChat} onHide={props.handleShowChat} placement="start" title="Chat">
                {!chatStarted ? (
                    <ChatLauncher onChatStarted={handleChatStarted} user={props.user}/>
                ) : (
                    <div>
                        <ChatDisplayer messages={messages}/>
                        <ChatSender onMessageEntered={handleMessageEntered}/>
                    </div>
                )}
            </Offcanvas>
        </div>
    );
};

export default Chatter;