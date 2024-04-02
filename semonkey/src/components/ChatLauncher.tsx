import React from "react";

const ChatLauncher = (props: {
    initialName: string,
    initialEmail: string,
    onChatStarted: (name: string, email: string) => void
}) => {
    return (
        <div>
            <form>
                <label>Nom</label>
                <input type="text" name="name" defaultValue={props.initialName}/>
                <label>Email</label>
                <input type="email" name="email" defaultValue={props.initialEmail}/>

                <button onClick={(e) => {
                    e.preventDefault();
                    const name = (document.querySelector('input[name="name"]') as HTMLInputElement).value;
                    const email = (document.querySelector('input[name="email"]') as HTMLInputElement).value;
                    props.onChatStarted(name, email);
                }}>Commencer le chat
                </button>
            </form>
        </div>
    )
}

export default ChatLauncher;