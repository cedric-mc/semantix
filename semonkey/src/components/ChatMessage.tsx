import React from "react"
import { Message } from "./Message";

const ChatMessage = (props: { message: Message }) => {
    return (
        <div>
            <p>
                {props.message.content}
            </p>
            <button onClick={() => {
                navigator.clipboard.writeText(props.message.content).then(r => {
                    console.log('copied')
                }
                )
            }}>Copier</button>
        </div>
    );
}

export default ChatMessage;