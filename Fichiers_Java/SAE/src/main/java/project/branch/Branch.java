package project.branch;

public class Branch {
    private final String word1;
    private final String word2;
    private final float score;

    public Branch(String word1, String word2, float score) {
        if (word1.isEmpty() || word2.isEmpty() ) {
            throw new IllegalArgumentException("You need two words to build a new branch.");
        }
        if (score < 0) {
            throw new IllegalArgumentException("The score is always positive.");
        }
        this.word1 = word1;
        this.word2 = word2;
        this.score = score;
    }

    public float getScore() {
        return score;
    }

    public String getWord1() {
        return word1;
    }

    public String getWord2() {
        return word2;
    }

    public String getOtherWord(String word) {
        if (word.isEmpty()) {
            throw new IllegalArgumentException("You need a word to compare.");
        }
        return word.equals(word2) ? word1 : word2;
    }
}
