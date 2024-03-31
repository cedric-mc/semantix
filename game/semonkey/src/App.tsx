import React, {useEffect, useState} from 'react';
import AddWord from './AddWord';
import "./style.css";
import "./css_game.css";
import ScoreBoard from "./ScoreBoard";
import { User } from "./User";
import UserDisplay from "./UserDisplay";
import { Button } from 'react-bootstrap';

function App() {
    const [word, setWord] = React.useState("");
    const [show, setShow] = React.useState(false);
    const [user, setUser] = useState<User | null>(null);

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);

    useEffect(() => {
        fetch('../')
            .then(response => response.json())
            .then(data => {
                const user = new User(data.idUser, data.pseudo, data.email, data.year, data.imageData);
                setUser(user);
            });
    }, []);

    if (!user) {
        return <div>Loading...</div>;
    }

    return (
        <div className="game-body">
            <AddWord/>
            <Button variant="primary" onClick={handleShow}>Afficher les informations</Button>
            <ScoreBoard show={show} handleClose={handleClose}/>
            <br></br>
            <Button className="btn btn-danger">Fin de partie</Button>
            <UserDisplay user={user}/>
        </div>
    );
}

export default App;
