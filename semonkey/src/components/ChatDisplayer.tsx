import React from "react";
import "./style/ChatDisplayer.css";
import ChatMessageDisplayer from "./ChatMessageDisplayer";
import {Message} from "./Message";

const ChatDisplayer = (props: { messages: Message[] }) => {
    return (
        <div>
            {props.messages.map((message, index) => (
                <ChatMessageDisplayer key={index} message={message}/>
            ))}
        </div>
    );
};

export default ChatDisplayer;