import React from "react";
import {Button, Modal} from "react-bootstrap";

const EndingGame = () => {
    const [showModal, setShowModal] = React.useState(false);

    const handleCloseModal = () => setShowModal(false);
    const handleReplay = () => {
        // Handle replay logic here
    };

    return (
        <Modal show={showModal} onHide={handleCloseModal} centered>
            <Modal.Header closeButton>
                <Modal.Title>Fin de la Partie</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                Bravo ! Votre score final est de {/* Score goes here */} point(s).
                Relèverez-vous le défi à nouveau ?
            </Modal.Body>
            <Modal.Footer>
                <Button variant="secondary" onClick={handleCloseModal}>Terminer la partie</Button>
                <Button variant="info" onClick={handleReplay}>Rejouer</Button>
            </Modal.Footer>
        </Modal>
    );
}

export default EndingGame;