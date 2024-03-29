import React from "react";
// import FloatingLabel from "react-bootstrap/FloatingLabel";
// import Form from "react-bootstrap/Form";

const AddWord = () => {
    return (
        <div className="add-word">
            <div className="form-floating mb-3">
                <input type="text" className="form-control" id="word" name="word" placeholder="Nouveau mot" required />
                <label form="word">Nouveau mot</label>
            </div>
            {/*<FloatingLabel controlId="word" label="Email address" className="form-control" />*/}
            {/*    <Form.Control type="email" placeholder="name@example.com" />*/}
            {/*</FloatingLabel>*/}
            {/*<FloatingLabel controlId="floatingPassword" label="Password">*/}
            {/*    <Form.Control type="password" placeholder="Password" />*/}
            {/*</FloatingLabel>*/}
        </div>
    );
}

export default AddWord;