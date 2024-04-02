import React from "react"

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