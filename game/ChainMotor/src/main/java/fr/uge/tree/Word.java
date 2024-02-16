package fr.uge.tree;

import java.util.Objects;

/**
 * @param word
 * word : Représente un mot dans l’arbre recouvrant maximal
 * lang = fr
 * @author Mamadou BA
 * @author Cédric MARIYA CONSTANTINE
 * @author Abdelrahim RICHE
 * @author Vincent SOUSA
 * @author Yacine ZEMOUCHE
 */
public record Word(String word) {

    /**
     * @return the word
     *
     * Méthode toString pour afficher un mot
     */
    @Override
    public String toString() {
        return word;
    }

    /**
     * @param o
     * o : Objet à comparer
     * @return
     *
     * Méthode equals pour comparer deux mots
     */
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Word word1 = (Word) o;
        return Objects.equals(word, word1.word);
    }
}
