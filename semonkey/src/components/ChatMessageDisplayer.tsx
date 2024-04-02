import React from "react";
import "./style/ChatMessageDisplayer.css";

const ChatMessageDisplayer = (props: { messages: { name: string, message: string }[], onMessageSent: (message: string) => void }) => {
    return (
        <div className="chat-message">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>
    );
};

export default ChatMessageDisplayer;