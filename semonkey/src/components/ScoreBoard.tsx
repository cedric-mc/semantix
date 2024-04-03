import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { Offcanvas } from "react-bootstrap";

interface Game {
    score: number;
    wordsArray: string[];
}

const ScoreBoard = (props: { show: boolean, handleClose: () => void, game: Game }) => {
    const game = props.game;

    return (
        <Offcanvas show={props.show} onHide={props.handleClose} placement="end" title="ScoreBoard" id="offcanvasRight" className="custom-offcanvas">
            <Offcanvas.Header closeButton>
                <Offcanvas.Title>Informations de la partie</Offcanvas.Title>
            </Offcanvas.Header>
            <Offcanvas.Body className="custom-offcanvas-body">
                <p>Score actuel : {game.score}</p>
                <p>Nombre de mots : {game.wordsArray.length}</p>
                <p>Dernier mot : {game.wordsArray.length > 0 ? game.wordsArray[game.wordsArray.length - 1] : "Aucun mot entré"}</p>
                <p>Nombre de mots restants : {7 - game.wordsArray.length}</p>
            </Offcanvas.Body>
        </Offcanvas>
    );
}

export default ScoreBoard;
