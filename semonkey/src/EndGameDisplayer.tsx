import React from "react";
import {Button, Modal} from "react-bootstrap";

const EndGameDisplayer = (props: {showModal: boolean, handleCloseModal: () => void, handleReplay: () => void}) => {
    const {showModal, handleCloseModal, handleReplay} = props;

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
                <Button variant="info" onClick={handleReplay}>Rejouer</Button>
                <Button variant="secondary" onClick={handleCloseModal}>Terminer la partie</Button>
            </Modal.Footer>
        </Modal>
    );
}

export default EndGameDisplayer;