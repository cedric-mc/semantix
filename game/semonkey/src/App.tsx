import React from 'react';
import AddWord from './AddWord';
import "./style.css";
import "./css_game.css";
import ScoreBoard from "./ScoreBoard";
import { Button } from 'react-bootstrap';

function App() {
    const [word, setWord] = React.useState("");
    const [show, setShow] = React.useState(false);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    return (
        <div className="game-body">
            <AddWord/>
            <Button variant="primary" onClick={handleShow}>Open ScoreBoard</Button>
            <ScoreBoard show={show} handleClose={handleClose} />
        </div>
    );
}

export default App;
