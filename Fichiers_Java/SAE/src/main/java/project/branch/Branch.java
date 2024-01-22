package project.branch;

public record Branch(String word1, String word2, float score) {
    // Bloc d'initialisation utilisé pour imposer des conditions à la création d'un objet Branch
    public Branch {
        // Vérification que les deux mots ne sont pas vides
        if (word1.isEmpty() || word2.isEmpty()) {
            throw new IllegalArgumentException("You need two words to build a new branch.");
        }
        // Vérification que le score est toujours positif
        if (score < 0) {
            throw new IllegalArgumentException("The score is always positive.");
        }
    }

    // Méthode pour obtenir l'autre mot dans la branche en fonction d'un mot donné
    public String getOtherWord(String word) {
        // Vérification que le mot n'est pas vide
        if (word.isEmpty()) {
            throw new IllegalArgumentException("You need a word to compare.");
        }
        // Retourne l'autre mot que celui spécifié en paramètre
        return word.equals(word2) ? word1 : word2;
    }

    // Redéfinition de la méthode toString pour obtenir une représentation textuelle de l'objet
    @Override
    public String toString() {
        return "Branch: " + word1 + ", " + word2 + ": " + score;
    }
}
