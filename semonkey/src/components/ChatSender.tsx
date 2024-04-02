import React from "react";

const ChatSender = (props: { onMessageEntered: (message: string) => void }) => {
    const [message, setMessage] = React.useState('');

    const handleMessageChange = (event: { target: { value: React.SetStateAction<string>; }; }) => {
        setMessage(event.target.value);
    };
    const handleKeyPress = (event: { key: string; }) => {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

    const sendMessage = () => {
        if (message.trim() === '') {
            props.onMessageEntered(message);
            setMessage('');
        }
    };

    return (
        <div>
            <input type="text" value={message} onChange={handleMessageChange} onKeyPress={handleKeyPress} placeholder="Entrer votre message" />
            <button onClick={sendMessage}>Send</button>
        </div>
    );
};

export default ChatSender;