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

    fetch("https://perso-etudiant.u-pem.fr/~mariyaconsta02/semantix/")
        .then(response => response.json())
        .then(data => {
            console.log(data.field1);
            console.log(data.field2);
        });

    return (
        <div className="game-body">
            <AddWord/>
            <Button variant="primary" onClick={handleShow}>Afficher les informations</Button>
            <ScoreBoard show={show} handleClose={handleClose} />
            <br></br>
            <Button className="btn btn-danger">Fin de partie</Button>
        </div>
    );
}

export default App;
