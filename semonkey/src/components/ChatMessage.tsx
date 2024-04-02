import React from "react"

interface Message {
    kind: 'received' | 'sent'
    content: string
    date: Date
}

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