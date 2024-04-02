import React from "react";
import {Button, CloseButton, Modal} from "react-bootstrap";

const EndGameDisplayer = (props: {showModal: boolean, handleCloseModal: () => void, handleReplay: () => void}) => {
    const {showModal, handleCloseModal, handleReplay} = props;

    return (
        <Modal show={showModal} onHide={handleCloseModal} centered>
            <Modal.Header>
                <Modal.Title>Fin de la Partie</Modal.Title>
                <CloseButton onClick={handleCloseModal} className="btn-close-white" />
            </Modal.Header>
            <Modal.Body>
                Bravo ! Votre score final est de {/* Score goes here */} point(s).
                Relèverez-vous le défi à nouveau ?
            </Modal.Body>
            <Modal.Footer>
                <div className="row justify-content-between">
                    <div className="col-auto">
                        <Button variant="info" onClick={handleReplay}>Rejouer</Button>
                    </div>
                    <div className="col-auto">
                        <Button variant="secondary" onClick={handleCloseModal}>Terminer la partie</Button>
                    </div>
                </div>
            </Modal.Footer>
        </Modal>
    );
}

export default EndGameDisplayer;