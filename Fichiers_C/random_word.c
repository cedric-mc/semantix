#include <stdio.h>
#include <stdlib.h>
#include "fonctions.h"
#include <time.h>

#define MAX_WORD_LENGTH 50
#define FILENAME "depart.txt"

// Fonction pour obtenir un mot aléatoire à partir du fichier
char getRandomWord() {
    FILEfile = fopen(FILENAME, "r");
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

    // Choisir un mot aléatoire
    srand(time(NULL));
    int randomIndex = rand() % wordCount;
    rewind(file);

    int currentIndex = 0;
    while (fgets(word, MAX_WORD_LENGTH, file) != NULL) {
        if (currentIndex == randomIndex) {
            // Supprimer le caractère de saut de ligne à la fin du mot
            size_t length = strlen(word);
            if (length > 0 && word[length - 1] == '\n') {
                word[length - 1] = '\0';
            }

            // Fermer le fichier et retourner le mot sélectionné
            fclose(file);
            return strdup(word);
        }
        currentIndex++;
    }

    // Fermer le fichier (ce cas ne devrait pas être atteint normalement)
    fclose(file);
    return NULL;
}


int main(int argc, char *argv[]) {
    printf("%s",getRandomWord());

    return 0;
}