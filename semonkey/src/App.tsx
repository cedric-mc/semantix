import React, {useEffect, useState} from 'react';
import {Button} from 'react-bootstrap';
import AddWord from './AddWord';
import "./style.css";
import "./css_game.css";
import ScoreBoard from "./ScoreBoard";
import {User} from "./User";
import EndGameDisplayer from "./EndGameDisplayer";
import axios from "axios";

function App() {
    const [word, setWord] = React.useState("");
    const [showInfo, setShowInfo] = React.useState(false);
    const [showEndGame, setShowEndGame] = React.useState(false);
    const [user, setUser] = useState<User | null>(null);

    const handleShowInfo = () => setShowInfo(true);
    const handleCloseInfo = () => setShowInfo(false);
    const handleEndGame = () => setShowEndGame(true);
    const handleCloseEndGame = () => setShowEndGame(false);

    /*
    <script>
            const userData = {
                idUser: <?php echo $user->getIdUser(); ?>,
                pseudo: "<?php echo $user->getPseudo(); ?>"
            }
            console.log(userData);
            // Local storage
            localStorage.setItem('userData', JSON.stringify(userData));
        </script>
     */
    useEffect(() => {
        const userData = JSON.parse(localStorage.getItem('userData') || '{}');
        setUser(new User(1, userData.pseudo));
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
