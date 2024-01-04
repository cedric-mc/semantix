#include <stdio.h>
#include "fonctions.c"

int main(int argc, char *argv[]) {
    if (argc != 4) {
        fprintf(stderr, "Usage: %s model_file index_file game_file new_word\n", argv[0]);
        exit(EXIT_FAILURE);
    }

    const char *modelFile = argv[1];
    const char *indexFile = argv[2];
    const char *newWord = argv[3];

    add_word(modelFile, indexFile, newWord);

    return 0;
}