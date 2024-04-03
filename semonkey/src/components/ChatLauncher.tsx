import React, {useEffect, useState} from "react";
import { User } from "../User";

interface ChatLauncherProps {
    user: User;
    onChatStarted: (name: string) => void;
}

const ChatLauncher = (props: ChatLauncherProps) => {
    const [name, setName] = useState(props.user.getPseudo);

    useEffect(() => {
        setName(props.user.getPseudo());
    }, [props.user]);

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        props.onChatStarted(name);
    };

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setName(event.target.value);
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <label>Nom</label>
                <input type="text" value={name} onChange={handleChange} />

                <button type="submit">Commencer le chat</button>
            </form>
        </div>
    );
}

export default ChatLauncher;