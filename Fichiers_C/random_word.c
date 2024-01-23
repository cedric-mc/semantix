#include <stdio.h>
#include <stdlib.h>
#include "fonctions.h"
#include <ctype.h>
#include <time.h>

#define MAX_WORD_LENGTH 50
#define MIN_WORD_LENGTH 5

char* getRandomWord(const char* filename) {
    FILE* file = fopen(filename, "r");
    if (file == NULL) {
        perror("Erreur lors de l'ouverture du fichier");
        return NULL;
    }

    char line[MAX_WORD_LENGTH];
    char* selectedWord = NULL;
    int count = 0;

    srand(time(NULL)); // Initialiser le générateur de nombres aléatoires

    while (fgets(line, sizeof(line), file) != NULL) {
        // Diviser la ligne en mots
        char* token = strtok(line, " \t\n");
        while (token != NULL) {
            // Vérifier si le mot a plus de 5 caractères et ne contient que des lettres
            if (strlen(token) > MIN_WORD_LENGTH && isalpha(*token)) {
                count++;
                // Choisir aléatoirement ce mot
                if (rand() % count == 0) {
                    if (selectedWord != NULL) {
                        free(selectedWord);
                    }
                    selectedWord = strdup(token);
                }
            }
            token = strtok(NULL, " \t\n");
        }
    }

    fclose(file);

    return selectedWord;
}


int main(int argc, char *argv[]) {
    const char *modelFile = argv[1];
    extractWordsAndOffsets(modelFile, "words.txt");
    printf("%s",getRandomWord("words.txt"));

    return 0;
}