#include <stdio.h>
#include "fonctions.h"

int main(int argc, char *argv[]) {
    if (argc != 3) {
        if (argc == 1) {
            // Si aucun paramètre n'est donné, imprimer le nom des auteurs
            print_authors();
            // Lancer la fonction de test minimaliste qui test la similarité orthographique
            run_minimal_test();
        } else{
            fprintf(stderr, "Usage: %s model_file new_word\n", argv[0]);
            printf(" where new_word is a string in the model_file\n");
            exit(EXIT_FAILURE);
        }
    }else{

        const char *modelFile = argv[1];
        const char *newWord = argv[2];

        add_word(modelFile, newWord);
    }

    return 0;
}
