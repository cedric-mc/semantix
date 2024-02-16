package fr.uge.main;

import fr.uge.tree.Edge;
import fr.uge.tree.MaximumSpanningTree;
import fr.uge.tree.Word;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Classe de Test
 * @see Test
 * lang = fr
 * @author Mamadou BA
 * @author Cédric MARIYA CONSTANTINE
 * @author Abdelrahim RICHE
 * @author Vincent SOUSA
 * @author Yacine ZEMOUCHE
 */
public class Test {
    /**
     * Méthode principale :
     * @param args
     *
     * Test de la classe MaximumSpanningTree et BestPath pour trouver le meilleur chemin entre deux mots.
     */
    public static void main(String[] args) {
        // Création d’un arbre recouvrant maximal avec le mot A et le mot B
        MaximumSpanningTree exampleMst = new MaximumSpanningTree(new Word("A"), new Word("B"));
        // Ajout d’une arête entre le mot A et le mot B avec une similarité de 10
        exampleMst.addEdge(new Edge(new Word("A"), 10, new Word("B")));
        // Ajout d’une arête entre le mot A et le mot C avec une similarité de 30
        // Ajout d’une arête entre le mot B et le mot C avec une similarité de 20
        List<Edge> edges = new ArrayList<>();
        edges.add(new Edge(new Word("C"), 30, new Word("A")));
        edges.add(new Edge(new Word("C"), 20, new Word("B")));
        exampleMst.addWord(new HashMap<>(Map.of(new Word("A"), edges))); // Ajout des arêtes pour le mot C
        edges.clear(); // On vide la liste d’arêtes
        // Ajout d’une arête entre le mot A et le mot D avec une similarité de 5
        // Ajout d’une arête entre le mot B et le mot D avec une similarité de 50
        // Ajout d’une arête entre le mot C et le mot D avec une similarité de 40
        edges.add(new Edge(new Word("D"), 5, new Word("A")));
        edges.add(new Edge(new Word("D"), 50, new Word("B")));
        edges.add(new Edge(new Word("D"), 40, new Word("C")));
        exampleMst.addWord(new HashMap<>(Map.of(new Word("A"), edges))); // Ajout des arêtes pour le mot D
        // Création de la classe BestPath pour trouver le meilleur chemin entre le mot de départ et le mot de cible
        BestPath bestPath = new BestPath(exampleMst);
        bestPath.printPathAndScore(); // Affichage du chemin et du score
    }
}
