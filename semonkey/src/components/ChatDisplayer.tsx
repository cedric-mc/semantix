import React from "react";
import "./style/ChatDisplayer.css";
import ChatMessageDisplayer from "./ChatMessageDisplayer";

const ChatDisplayer = (props: { messages: { name: string, message: string }[], onMessageSent: (message: string) => void }) => {
    return (
        <div className="chat-displayer">
            <ChatMessageDisplayer messages={props.messages} onMessageSent={props.onMessageSent} />
        </div>
    );
};

export default ChatDisplayer;