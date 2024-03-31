import React from "react";
import { User } from './User';

interface UserDisplayProps {
    user: User;
}

const UserDisplay = (props: UserDisplayProps) => {
    const { user } = props;
    return (
        <div>
            <h1>{user.getPseudo()}</h1>
            <p>Email: {user.getEmail()}</p>
            <p>Year: {user.getYear()}</p>
            <img src={user.getImageSrc()} alt="User" />
        </div>
    );
}

export default UserDisplay;