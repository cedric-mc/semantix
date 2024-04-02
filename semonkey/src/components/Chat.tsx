import React, {useCallback, useState} from 'react';
import useWebSocket, {ReadyState} from "react-use-websocket";
import {Offcanvas} from "react-bootstrap";

const Chat = () => {
    const [messageHistory, setMessageHistory] = useState([]);

    const {
        sendMessage,
        readyState,
    } = useWebSocket('ws://localhost:8080', {
        onMessage: (event) => {
            setMessageHistory((prev) => [...prev, event.data]);
        },
        shouldReconnect: (closeEvent) => true,
    });

    const handleClickSendMessage = useCallback(() => sendMessage('Hello'), []);

    return (
        <div>
            <button onClick={handleClickSendMessage} disabled={readyState !== ReadyState.OPEN}>Send Message</button>
            {messageHistory.map((message, index) => <div key={index}>{message}</div>)}
        </div>
    );
}

export default Chat;