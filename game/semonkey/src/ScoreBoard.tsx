import React from "react";
import { Offcanvas } from "react-bootstrap";

const ScoreBoard = (props: { show: boolean, handleClose: () => void }) => {
    return (
        <Offcanvas show={props.show} onHide={props.handleClose} placement="end" title="ScoreBoard" id="offcanvasRight"/* className="custom-offcanvas"*/ variant="dark">
            <Offcanvas.Header closeButton>
                <Offcanvas.Title>ScoreBoard</Offcanvas.Title>
            </Offcanvas.Header>
            <Offcanvas.Body>
                <h2>Score</h2>
                <p>Nombre de mots trouvés : 0</p>
                <p>Nombre de mots restants : 0</p>
            </Offcanvas.Body>
        </Offcanvas>
    );
}

export default ScoreBoard;
