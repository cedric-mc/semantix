import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { Offcanvas, CloseButton } from "react-bootstrap";

const ScoreBoard = (props: { show: boolean, handleClose: () => void }) => {
    const closeButtonStyle = {
        backgroundColor: 'rgba(255, 0, 0, 1)', // Red color with transparency
        border: 'none',
        padding: '12px 12px',
        borderRadius: '5px',
        transition: 'background-color 2s ease',
        color: '#fff', // White text color
    };
    
    const closeButtonHoverStyle = {
        backgroundColor: 'rgba(255, 0, 0, 1)', // Change color on hover
        cursor: 'pointer',
    };

    return (
        <Offcanvas variant="dark" show={props.show} onHide={props.handleClose} placement="end" title="ScoreBoard" id="offcanvasRight" className="custom-offcanvas">
            <Offcanvas.Header>
                <Offcanvas.Title>Informations</Offcanvas.Title>
                <CloseButton style={closeButtonStyle} onMouseOver={(e) => e.currentTarget.style.backgroundColor = closeButtonHoverStyle.backgroundColor} onMouseOut={(e) => e.currentTarget.style.backgroundColor = closeButtonStyle.backgroundColor} onClick={props.handleClose} aria-label="Close" />
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
