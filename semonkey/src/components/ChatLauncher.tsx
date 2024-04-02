import React, { useState } from "react";

const ChatLauncher = (props: { initialName: string, initialEmail: string, onChatStarted: (name: string, email: string) => void }) => {
    const [name, setName] = useState(props.initialName);
    const [email, setEmail] = useState(props.initialEmail);

    const handleNameChange = (event: { target: { value: React.SetStateAction<string>; }; }) => {
        setName(event.target.value);
    };
    const handleEmailChange = (event: { target: { value: React.SetStateAction<string>; }; }) => {
        setEmail(event.target.value);
    };
    const handleSubmit = (event: { preventDefault: () => void; }) => {
        event.preventDefault();
        props.onChatStarted(name, email);
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <label>Nom</label>
                <input type="text" name={name} onChange={handleNameChange}/>
                <label>Email</label>
                <input type="email" name={email} onChange={handleEmailChange}/>

                <button type="submit" onClick={handleSubmit}>Commencer le chat</button>
            </form>
        </div>
    )
}

export default ChatLauncher;