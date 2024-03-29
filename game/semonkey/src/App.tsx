import React from 'react';
import AddWord from './AddWord';
import "./style.css";
import "./css_game.css";
import ScoreBoard from "./ScoreBoard";

function App() {
    const [word, setWord] = React.useState("");

    return (
        <div className="game-body">
            <AddWord />
            <ScoreBoard />
        </div>
    );
}

export default App;
