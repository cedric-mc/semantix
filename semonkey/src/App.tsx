import React, {useEffect, useState} from 'react';
import {Button, Modal, Offcanvas} from 'react-bootstrap';
import AddWord from './AddWord';
import "./style.css";
import "./css_game.css";
import ScoreBoard from "./ScoreBoard";
import {User} from "./User";
import UserDisplay from "./UserDisplay";
import EndGameDisplayer from "./EndGameDisplayer";

function App() {
    const [word, setWord] = React.useState("");
    const [showInfo, setShowInfo] = React.useState(false);
    const [showEndGame, setShowEndGame] = React.useState(false);
    const [user, setUser] = useState<User | null>(null);

    const handleShowInfo = () => setShowInfo(true);
    const handleCloseInfo = () => setShowInfo(false);
    const handleEndGame = () => setShowEndGame(true);
    const handleCloseEndGame = () => setShowEndGame(false);

    useEffect(() => {
        // Fetch user data from the server
        fetch("https://jsonplaceholder.typicode.com/users/1")
            .then(response => response.json())
            .then(data => {
                const user = new User(data.id, data.name, data.email, data.username);
                setUser(user);
            });
    }, []);

    return (
        <div className="parent">
            <div className="player-info">
                {/*<UserDisplay user={user}/>*/}
                <Button variant="primary" onClick={handleShowInfo}>Informations de la partie</Button>
            </div>
            <div className="addWord">
                <AddWord/>
            </div>
            <div className="graph" id="container">
                {/* Graph component goes here */}
            </div>
            <Button type="button" className="endGameBtn" onClick={handleEndGame}>
                Finir la partie
            </Button>
            <ScoreBoard show={showInfo} handleClose={handleCloseInfo} game={{score: 0, wordsArray: []}}/>
            <EndGameDisplayer showModal={showEndGame} handleCloseModal={handleCloseEndGame} handleReplay={() => {
            }}/>
        </div>
    );
}

export default App;
