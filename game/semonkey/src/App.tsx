import React from 'react';
import AddWord from './AddWord';
import './App.css';

function App() {
    return (
        <div className="game-body">
            <header>
                <h1>Le jeu du singe</h1>
            </header>
            <main>
                <AddWord />
            </main>
        </div>
    );
}

export default App;
