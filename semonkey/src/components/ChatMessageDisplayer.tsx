import React from "react";
import { Message } from "./Message";
import "../style/ChatMessageDisplayer.css";

const ChatMessageDisplayer = (props: { message: Message }) => {
    const handleCopy = () => {
        navigator.clipboard.writeText(props.message.content).then(r => console.log('Copied')).catch(e => console.error('Error copying'));
    };

    return (
        <div style={{
            textAlign: props.message.kind === 'received' ? 'left' : 'right',
            backgroundColor: props.message.kind === 'received' ? '#f2f2f2' : '#4CAF50',
            padding: '10px',
            margin: '10px' }}>
            <p>{props.message.content}</p>
            <button onClick={handleCopy}>Copy Message</button>
        </div>
    );
};

export default ChatMessageDisplayer;