import React from "react";
import { User } from './User';
import "./UserDisplayer.css";

const UserDisplayer = (props: { user: User }) => {
    const { user } = props;
    return (
        <div className="player-info">
            <img src={user.getImageSrc()} alt="User"/>
            <p>{user.getPseudo()}</p>
        </div>
    );
}

export default UserDisplayer;