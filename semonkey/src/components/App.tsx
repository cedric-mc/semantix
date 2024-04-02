import React, {useEffect, useState} from 'react';
import {Button} from 'react-bootstrap';
import AddWord from './AddWord';
import "../style/style.css";
import "../style/css_game.css";
import ScoreBoard from "./ScoreBoard";
import {User} from "../User";
import EndGameDisplayer from "./EndGameDisplayer";
import ChatManager from "./ChatManager";

function App() {
    const [word, setWord] = React.useState("");
    const [showInfo, setShowInfo] = React.useState(false);
    const [showEndGame, setShowEndGame] = React.useState(false);
    const [user, setUser] = useState<User | null>(null);
    const [chatManager, setChatManager] = useState<ChatManager | null>(null);

    const handleShowInfo = () => setShowInfo(true);
    const handleCloseInfo = () => setShowInfo(false);
    const handleEndGame = () => setShowEndGame(true);
    const handleCloseEndGame = () => setShowEndGame(false);

    useEffect(() => {
        let userData = localStorage.getItem('userData');
        if (userData) {
            let user = JSON.parse(userData);
            setUser(new User(user.idUser, user.pseudo));
        }
        // Initialisation du chatManager ici.
        setChatManager(new ChatManager());
    }, []);

    if (!user) {
        return <div>Loading...</div>;
    }

    return (
        <div className="parent">
            <div className="player-info">
                <img className="me-3" src={user.getImageSrc()} alt="User"/>
                <p className="username me-3">{user.getPseudo()}</p>
                <Button variant="primary" onClick={handleShowInfo}>Informations de la partie</Button>
            </div>
            <div className="addWord">
                <AddWord/>
            </div>
            <div className="graph" id="container">
                {/* Graph component goes here */}
            </div>
            <button type="button" className="endGameButton" onClick={handleEndGame}>Finir la partie</button>
            <ScoreBoard show={showInfo} handleClose={handleCloseInfo} game={{score: 0, wordsArray: []}}/>
            <EndGameDisplayer showModal={showEndGame} handleCloseModal={handleCloseEndGame} handleReplay={() => {
            }}/>

        </div>
    );
}

export default App;
