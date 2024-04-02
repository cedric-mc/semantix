import React from "react";
import "./style/ChatDisplayer.css";
import ChatMessageDisplayer from "./ChatMessageDisplayer";

const ChatDisplayer = (props: { messages: Message[] }) => {
    return (
        <div>
            {messages.map((message, index) => (
                <ChatMessageDisplayer key={index} message={message}/>
            ))}
        </div>
    );
};

export default ChatDisplayer;