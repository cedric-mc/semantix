import React from "react";

// word en props
const AddWord = () => {
    const [word, setWord] = React.useState("");

    return (
        <div className="add-word">
            <div className="form-floating mb-3">
                <input type="text" className="form-control" id="word" name="word" placeholder="Nouveau mot" required/>
                <label form="word">Nouveau mot</label>
            </div>
            <button type="submit" className="btn btn-success" onClick={() => setWord(word)}>Insérer un nouveau mot</button>
        </div>
    );
}

export default AddWord;