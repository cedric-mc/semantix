#include <stdio.h>
#include <stdlib.h>
#include "fonctions.h"

int main(int argc, char *argv[]) {
    if (argc == 1) {
        // Si aucun paramètre n'est donné, imprimer le nom des auteurs
        print_authors();
        // Lancer la fonction de test minimaliste qui test la similarité orthographique
        run_minimal_test();
    } else {
        if (argc==2 && strcmp("--help", argv[1])==0){
            fprintf(stderr, "Usage: %s model_file word1 word2 ...\n", argv[0]);
            printf(" where word1, word2 are two strings in the model_file\n");
            exit(EXIT_FAILURE);
        }else{
            const char *modelFile = argv[1];
            int numWords = argc - 2;
            char **words = argv + 2;

            new_game(modelFile, numWords, words, 1);
        }
    }

    return 0;
}