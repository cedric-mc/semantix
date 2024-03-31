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
        // Accédez aux données `userData` qui ont été incluses dans votre page HTML
        // @ts-ignore
        const userData = window.userData;

        // Créez une instance de l'objet User à partir des données userData
        const userJson = new User(
            userData.idUser,
            userData.pseudo,
            userData.email,
            parseInt(userData.year), // Assurez-vous de convertir l'année en nombre si nécessaire
            userData.imageData
        );

        // Définissez l'utilisateur dans l'état
        setUser(userJson);
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
