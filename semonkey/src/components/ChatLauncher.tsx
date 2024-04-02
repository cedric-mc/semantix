import React, {useEffect, useState} from "react";
import { User } from "../User";

interface ChatLauncherProps {
    user: User;
    onChatStarted: (name: string) => void;
}

const ChatLauncher = (props: { user: User, onChatStarted: (name: string) => void }) => {
    const [name, setName] = useState(props.user.getPseudo);

    useEffect(() => {
        setName(props.user.getPseudo());
    }, [props.user]);

    const handleSubmit = (event: { preventDefault: () => void; }) => {
        event.preventDefault();
        props.onChatStarted(name);
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <label>Nom</label>
                <input type="text" value={name} readOnly/>

                <button type="submit" onClick={handleSubmit}>Commencer le chat</button>
            </form>
        </div>
    )
}

export default ChatLauncher;