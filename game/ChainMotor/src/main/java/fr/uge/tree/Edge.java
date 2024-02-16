package fr.uge.tree;

import java.util.Objects;

/**
 * @param sourceWord : le mot source
 * @param similarity : la similarité
 * @param targetWord : le mot cible
 * @author Mamadou BA
 * @author Cédric MARIYA CONSTANTINE
 * @author Abdelrahim RICHE
 * @author Vincent SOUSA
 * @author Yacine ZEMOUCHE
 *
 * Classe qui représente une arête dans un arbre recouvrant maximal
 */
public record Edge(Word sourceWord, double similarity, Word targetWord) {

    /**
     * @return String
     *
     * Méthode qui retourne une représentation textuelle de l'objet
     */
    @Override
    public String toString() {
        return sourceWord + " -> " + targetWord + " (" + similarity + ")";
    }

    /**
     * @param o
     * o : l'objet à comparer
     * @return
     *
     * Méthode qui compare deux objets
     */
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Edge edge = (Edge) o;
        return Objects.equals(sourceWord, edge.sourceWord) && Objects.equals(targetWord, edge.targetWord) && similarity == edge.similarity;
    }

    /**
     * @return
     *
     * Méthode qui retourne le hashcode de l'objet
     */
    @Override
    public int hashCode() {
        return Objects.hash(sourceWord, similarity, targetWord);
    }
}
