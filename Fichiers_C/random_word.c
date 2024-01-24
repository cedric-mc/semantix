#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#define MAX_WORD_LENGTH 50
#define FILENAME "depart.txt"

// Fonction pour obtenir un mot aléatoire à partir du fichier
char* getRandomWord() { // Retourne un pointeur char*
    FILE *file = fopen(FILENAME, "r");
    if (file == NULL) {
        perror("Erreur lors de l'ouverture du fichier");
        exit(EXIT_FAILURE);
    }

    // Compter le nombre de mots dans le fichier
    int wordCount = 0;
    char word[MAX_WORD_LENGTH];
    while (fgets(word, MAX_WORD_LENGTH, file) != NULL) {
        wordCount++;
    }

    // Vérifier que le fichier n'est pas vide
    if (wordCount == 0) {
        fclose(file);
        return NULL;
    }

    // Choisir un mot aléatoire
    srand(time(NULL));
    int randomIndex = rand() % wordCount;
    rewind(file);

    int currentIndex = 0;
    while (fgets(word, MAX_WORD_LENGTH, file) != NULL) {
        if (currentIndex == randomIndex) {
            fclose(file);
            size_t length = strlen(word);
            if (length > 0 && word[length - 1] == '\n') {
                word[length - 1] = '\0';
            }
            return strdup(word); // Retourne une copie du mot
        }
        currentIndex++;
    }

    fclose(file);
    return NULL;
}

int main() {
    char* randomWord = getRandomWord();
    if (randomWord != NULL) {
        printf("%s\n", randomWord);
        free(randomWord); // Libérer la mémoire allouée
    } else {
        printf("Aucun mot trouvé.\n");
    }

    return 0;
}
